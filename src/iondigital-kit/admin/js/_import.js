import "babel-core/register";
import "babel-polyfill";
// import React from 'react';


class Queue{
    constructor(queue = []){
        this.queue = queue;
    }

    *[Symbol.iterator]() {
        yield *this.queue;
    }
}

export default class Importer extends React.Component {
  
    constructor(props){
        super(props)
		this.startImport = this.startImport.bind(this);
		this.ajax_widget = this.ajax_widget.bind(this);
		this.onInput = this.onInput.bind(this);
		this.toogleArea = this.toogleArea.bind(this);
		this.ajax_import_options = this.ajax_import_options.bind(this);
		this.ajax_import_posts = this.ajax_import_posts.bind(this);
		this.state = { output: '', activateLoading: false, error: false, stepNumber: iondigital.importer["import_step"], expanded: false, catchError: '' };
		
	}
	


    componentDidMount(){
        //these hold the ajax responses
        this.responseRaw = null;
        this.res = null;
        this.stepNumber = 0;
        this.qInst;

        this.nonceImportWidgets = iondigital['importer']['nonceImportWidgets']
        this.nonceImportThemeOptions = iondigital['importer']['nonceImportThemeOptions']
        this.nonceImportPostsPages = iondigital['importer']['nonceImportPostsPages']
        this.ajaxUrl = iondigital['importer']['ajaxUrl']



		//this is the ajax queue
        this.Iterator = (genFunc) => {

            const generator = genFunc();
            const _void = () => undefined;
            
            const subscribe = (next, complete = _void, error = _void) => {

                let {value, done} = generator.next();

                if(!done){
                    return value.then(next, () => subscribe(next, complete, error))
					.then( () => subscribe(next, complete, error), _ => {})
                    .catch(err => {  console.error(err)})
                }
                else{
                    return Promise.resolve(complete)
                }
            }

            return subscribe;
		}
		

		this.retryRequest = (request, step) => {
			let requests = [];
			let that = this;
			Array.from({length: 5}).map( (_, idx, arr) => { 
				if(idx === arr.length - 1){
					requests.push(request.bind(that, false)) 
				} 
				requests.push(request) 
			})
			const queueAjaxRetryRequest = new Queue(requests);

			//this is the ajax queue
			const iterator = (genFunc) => {

				const generator = genFunc();
				const _void = () => undefined;
				
				const subscribe = (next, complete, error = _void) => {

					let {value, done} = generator.next();

					if(!done){
						return value.then(next)
						.then( (val) => val , () => new Promise( resolve => setTimeout( _ => {
							resolve(subscribe(next, complete, error));
						}, 3000)))
						//.catch(err => { console.error(err)})
					}
					else{
						complete();
						return Promise.reject()
					}
				}

				return subscribe;
			}

			const stream$ = iterator(function* () {
					for(let q of queueAjaxRetryRequest){
						yield q();
					}
			});

			return stream$(val => new Promise( (res, rej) => {
					if(val.status == 500){
						rej(val);
					}else{
						res(val);
					}
				})
				,() => {
					that.setState({'error': true});
					that.setState({'output': that.state.output + '<i>' + iondigital.importer.import_data.import_posts_step + ' ' + step + ' of ' + that.state.stepNumber + '</i><i style="font-size: 10px; color: red;">Imported partially with Internal Server Error</i><br />'
					});
				}
			)
			
		}



    }

	ajax_import_posts(filename){

		let stepNumber = 0, requests = [];
		let that = this;
		Array.from({length: this.state.stepNumber}).map(( _ , idx) => {
			let stepNumber = idx + 1;
			const request = (attachments = true) => {
				let url = this.ajaxUrl, 
					info = {
						method: "POST",
						mode: "cors",
						headers: {
							'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'
						},
						body: 'action=Iondigital_ajax_import_posts_pages&_wpnonce='+ this.nonceImportPostsPages + '&step_number=' + stepNumber + '&number_of_steps=' + this.state.stepNumber  + '&filename=' + filename + '&attachments=' + attachments,
						credentials: 'same-origin'
					};

				return fetch(url, info);
			}

			requests.push(request);
		})
	
		const queueAjaxImportPages = new Queue(requests);

		const stream$ = this.Iterator(function* () {
				var chachedLastRequest = null;
				var i = 0;

				for(let q of queueAjaxImportPages){
					chachedLastRequest = q;
					if( typeof q === 'function'){
						q = q();
					}
					i++;
					yield q.then(data => {
						if(data.status == 500 ){
							return that.retryRequest(chachedLastRequest, i);
						}
						return data
					})
					 // If this is a promise, the execution will wait until resolved to continue
				}
			});


			return stream$(val => {
				console.log(val);
	
				return val.json().then(resp => {

					try {
						
						let id = resp['data']['id'],
							lastOutput = that.state['output'],
							translation = iondigital.importer.import_data;

						if(id && (typeof id == "boolean")){
							that.setState({'output': lastOutput + '<i>' + translation.import_posts_step + ' ' + resp['data']['supplemental'].stepNumber + ' of ' + resp['data']['supplemental'].numberOfSteps + '</i><br />'
							+ '<i style="display: none;"> Response Data: ' + resp['data'].data + '</i><br />'
							}); 

						}else if(id['errors'] &&  (typeof id == "object") && id.errors[Object.keys(id.errors)[0]]){
							that.setState({'output': lastOutput + '<i style="color:red">' + translation.import_posts_failed + '</i><br />' + translation.import_error + ' ' + id.errors[Object.keys(id.errors)[0]][0]
							});
							that.setState({error: true});
						}else{
							that.setState({'output': lastOutput + '<i style="color:red">' + translation.import_posts_failed + '</i><br />' + translation.import_error + ' ' + resp['data'].data });
							that.setState({error: true});
						}
	
	
					} catch (error) {
						
					 	that.setState({'output': lastOutput + '<i style="color:red">' + v.import_posts_failed + ' ' + translation.import_try_reload + ' </i><br />' });
					 	that.setState({error: true, catchError: '<i style="color:red">Catch an Error during runtime.</i>'});
					}
	
				})
	
			}).then(() => new Promise(res => setTimeout(res, 3000)));


		
	}

	ajax_import_options(filename) {
        //make the call for importing the options 
		let that = this;
        const request = (
            url = this.ajaxUrl, 
            info = {
				method: "POST",
				mode: "cors",
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'
				},
				body: 'action=Iondigital_ajax_import_theme_options&_wpnonce='+ this.nonceImportThemeOptions + '&filename=' + filename,
				credentials: 'same-origin'
			}
        ) => fetch(url, info)


        const queueAjaxImportThemeOptions = new Queue([request]);

		const stream$ = this.Iterator(function* () {
			var chachedLastRequest = null;
			var i = 0;

			for(let q of queueAjaxImportThemeOptions){
				chachedLastRequest = q;
				if( typeof q === 'function'){
					q = q();
				}
				i++;
				yield q.then(data => {
					if(data.status == 500 ){
						return that.retryRequest(chachedLastRequest, i);
					}
					return data
				})
				 // If this is a promise, the execution will wait until resolved to continue
			}
		});

		var decoder = new TextDecoder();
		
		return stream$(val => {

			if (val.status != 200 ){
				this.setState({error: true});
				this.setState({output: this.state.output + '<i style="color:red">' + val.statusText + '</i><br/>' })
			}
			return val.json().then(resp => {
				try {
					
					let id = resp['data']['id'],
						lastOutput = that.state['output'],
						translation = iondigital.importer.import_data;

					if(id && (typeof id == "boolean")){
						that.setState({'output': lastOutput + '<i>' + translation.import_theme_options_done + '</i><br />'
						+ '<i style="display: none;" > Response Data: ' + resp['data'].data + '</i><br />' });
					}else if(id['errors'] && id.errors[Object.keys(id.errors)[0]]){
						that.setState({'output': lastOutput + '<i style="color:red">' + translation.import_theme_options_error + ': '  + id.errors[Object.keys(id.errors)[0]][0] + '<br/><br/>' 
						});
						that.setState({error: true});
					}else{
						that.setState({'output': lastOutput + '<i style="color:red">' + translation.import_theme_options_failed + '</i><br />' });
						that.setState({error: true});
					}


				} catch (error) {
					
					that.setState({'output': lastOutput + '<i style="color:red">' + translation.import_theme_options_failed + '</i><br />' });
					that.setState({error: true, catchError: '<i style="color:red">Catch an Error during runtime.</i>'});
				}

			})

		}).then(() => new Promise(res => setTimeout(res, 3000)));

		
	}

    ajax_widget(filename){
        //make the call for importing the widgets and the menus
		let that = this;

        const request = (
            url = this.ajaxUrl,
            info = {
                method: "POST",
				mode: "cors",
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
					'X-Requested-With': 'XMLHttpRequest'
				},
				body: 'action=Iondigital_ajax_import_widgets&_wpnonce='+ this.nonceImportWidgets + '&filename=' + filename,
				credentials: 'same-origin'
			}
        ) => fetch(url, info);


        const queueAjaxImportWidgets = new Queue([request]);

		const stream$ = this.Iterator(function* () {
			var chachedLastRequest = null;
			var i = 0;

			for(let q of queueAjaxImportWidgets){
				chachedLastRequest = q;
				if( typeof q === 'function'){
					q = q();
				}
				i++;
				yield q.then(data => {
					if(data.status == 500 ){
						return that.retryRequest(chachedLastRequest, i);
					}
					return data
				})
				 // If this is a promise, the execution will wait until resolved to continue
			}
		});
		

        return stream$(val => {

			if (val.status != 200 ){
				this.setState({error: true});
				this.setState({output: this.state.output + '<i style="color:red">' + val.statusText + '</i><br/>' })
			}

			return val.json().then(resp => {
				try {
					
					let id = resp['data']['id'],
						lastOutput = that.state['output'],
						translation = iondigital.importer.import_data;
			
					if(id && (typeof id == "boolean")){
						that.setState({'output': lastOutput + '<i>' + translation.import_widgets_done + '</i><br />' +
						'<i style="display: none;" > Response Data: ' + resp['data'].data + '</i><br />' });
					}else if(id['errors'] && id.errors[Object.keys(id.errors)[0]]){
						that.setState({'output': lastOutput + '<i style="color:red">' + translation.import_widgets_error + ': '  + id.errors[Object.keys(id.errors)[0]][0] + '<br/><br/>' 
						});
						that.setState({error: true});
					}else{
						that.setState({'output': lastOutput + '<i style="color:red">' + translation.import_widgets_failed + '</i><br />' });
						that.setState({error: true});
					}


				} catch (error) {
					
					that.setState({'output': lastOutput + '<i style="color:red">' + translation.import_widgets_failed + '</i><br />' });

					that.setState({error: true, catchError: '<i style="color:red">Catch an Error during runtime.</i>'});
				}

			})

		}).then(() => new Promise(res => setTimeout(res, 3000)));

	}

    startImport(filename){
		return (async () => {

			var activate = confirm( 'Importing demo data will overwrite your current site content and options. Proceed anyway?' );

			if ( activate == false ) return false;
			
			this.setState({activateLoading: !!activate, importingFile: filename})
			try{
				//queue the calls
				await this.ajax_widget(filename);
				if ( !this.state.error )  await this.ajax_import_options(filename);
				if ( !this.state.error )  await this.ajax_import_posts(filename);

				if ( !this.state.error ){

					this.setState({output: this.state.output + '<h3>' + iondigital.importer.import_data.import_phew + '</h3><br /><p>' + iondigital.importer.import_data.import_success_note + iondigital.importer.import_data.import_success_warning + '</p>' })
				
				}else{
					this.setState({output: this.state.output + '<p>	&#x2110; Try to increase "PHP Max Execution Time"(180 sec is optimal, 30 sec(default) is not working) or/and number of importing steps at "More options" above.'});
				}
				this.setState({activateLoading: false});

			}catch(err){
				this.setState({output: this.state.output + '<i style="color:red">' + err + '</i><br/>' })
				this.setState({activateLoading: false});
			}

		})

	}
	
	onInput(e){
		let value = Math.max(Math.min(e.currentTarget.value, 400),0);
		this.setState({stepNumber: value})
		const request = (
            url = iondigital.wpRest.base + 'stepnumber',
            info = {
                method: "POST",
				mode: "cors",
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
					'X-Requested-With': 'XMLHttpRequest',
					'X-WP-Nonce': iondigital.wpRest.nonce
				},
				body: 'value='+ value + '&iondigital_nonce='+ iondigital.wpRest.iondigital_nonce,
				credentials: 'same-origin'
			}
		) => fetch(url, info);
		request().then(res => res.json())//.then(console.log);

	}

	toogleArea(){
		this.setState({'expanded': !this.state.expanded})
	}

    render() {
		let { output, activateLoading, stepNumber, expanded, catchError, error, importingFile } = this.state; 
        return (
			<div className="Ion-importer"> 
				<div dangerouslySetInnerHTML={{ __html: iondigital.dashboard.general['text3'] }}/>
				

				{iondigital.themeSupports.importer['import_info'] && Object.entries(iondigital.themeSupports.importer['import_info']).map( ([filename, info], idx) => {
					return (
						<div key={`${filename}-${idx}`} className="Ion-importer__item">
							<img className="Ion-importer__img" src={info['img']} />
							<div className="Ion-importer-info">
								<h3 className="Ion-importer-info__title">{info['title']}</h3>
								<p className="Ion-importer-info__description">{info['description']}</p>
								<a className="Ion-importer-info__link" href={info['link']} target="_blank">🔗 Demo</a>
								<p className="Ion-importer-info__btn"><button id={`Ion-import-demodata-button-${filename}`}  className={`btn btn-primary ${filename == importingFile && activateLoading ? 'animate ': ''} ${activateLoading ? 'noClick': ''}`} onClick={this.startImport(filename)}> {iondigital.dashboard.general['btn_import']} </button></p>
							</div>
						</div>
					)
				})
				}
				{!iondigital.themeSupports.importer['import_info'] && <p style={{color:"red"}}> The "importer"=>"import_info" param - is not dedined in theme support.</p>}
				<div className="Ion-toogle" aria-expanded={expanded}>
					<button className="Ion-toogle__button" onClick={this.toogleArea} >
						<span className="screen-reader-text">Toggle panel: More options</span>
						<h5 className="Ion-import__more">⚙️ More options: </h5>
					</button>
					{ expanded && <div className="">
						<span>Number of importing steps:   </span>	
						<input type="number" min="10" disabled={activateLoading} value={stepNumber} max="400" step="5" onChange={this.onInput} />
					</div>}
				</div>

				{ activateLoading && <div className="Bitstarter-loading-wrap">
					<span className="Bitstarter-loading Bitstarter-import-loading"></span>
					<div className="Bitstarter-import-wait" dangerouslySetInnerHTML={{ __html: iondigital.importer.import_data["import_wait"] }} /> 
				</div>
				}
				<div dangerouslySetInnerHTML={{ __html: output }} />
				{error && <div dangerouslySetInnerHTML={{ __html: catchError }} />}
			</div>
		)
    }

}
