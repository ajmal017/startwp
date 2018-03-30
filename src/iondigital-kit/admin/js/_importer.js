import "babel-core/register";
import "babel-polyfill";
import fromXML from "from-xml";

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
		console.log(iondigital)
		this.startImport = this.startImport.bind(this);
		this.ajax_widget = this.ajax_widget.bind(this);
		this.ajax_import_options = this.ajax_import_options.bind(this);
		this.ajax_import_posts = this.ajax_import_posts.bind(this);
		this.state = { output: '', activateLoading: false };
		
	}
	


    componentDidMount(){
        //these hold the ajax responses
        this.responseRaw = null;
        this.res = null;
        this.stepNumber = 0;
        this.numberOfSteps = 10;
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
                    return Promise.resolve(value).then(next)
                    .then( () => subscribe(next, complete, error))
                    .catch(err => error(err))
                }
                else{
                    return Promise.resolve(complete())
                }
            }

            return subscribe;
        }

    }

	ajax_import_posts(){

		let stepNumber = 0, requests = [];
		let that = this;
		Array.from({length: this.numberOfSteps}).map(( _ , idx) => {
			let stepNumber = idx + 1;
			const request = () => {
				let url = this.ajaxUrl, 
					info = {
						method: "POST",
						mode: "cors",
						headers: {
							'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'
						},
						body: 'action=Iondigital_ajax_import_posts_pages&_wpnonce='+ this.nonceImportPostsPages + '&step_number=' + stepNumber + '&number_of_steps=' + this.numberOfSteps,
						credentials: 'same-origin'
					};

				return fetch(url, info);
			}

			requests.push(request);
		})
	
		const queueAjaxImportPages = new Queue(requests);

			const stream$ = this.Iterator(function* () {
				for(let q of queueAjaxImportPages){
					if( typeof q === 'function'){
						q = q();
					}
					yield q; // If this is a promise, the execution will wait until resolved to continue
					
				}
			});

			var decoder = new TextDecoder();

			return stream$(val => {
				if (val.status != 200 ){
					this.setState({output: this.state.output + '<i style="color:red">' + val.statusText + '</i><br/>' })
				}
				let reader = val.body.getReader();
				return reader.read().then(result => {
	
					let xml = decoder.decode(result.value || new Uint8Array, {
						stream: false
					});
					let resp = fromXML.fromXML( xml.substr(xml.indexOf('?>') + 2));
					try {
						
						let id = resp['wp_ajax']['response']['import_posts_pages']['@id'];
						
						let lastOutput = that.state['output'];
						let translation = iondigital.importer.import_data;
						if(id === "1"){
							that.setState({'output': lastOutput + '<i>' + translation.import_posts_step + ' ' + resp['wp_ajax']['response']['import_posts_pages'].supplemental.stepNumber + ' of ' +resp['wp_ajax']['response']['import_posts_pages'].supplemental.numberOfSteps + '</i><br />' 
							+ '<i style="display: none;"> Response Data: ' + resp['wp_ajax']['response']['import_posts_pages'].response_data + '</i><br />'
							});

						}else if(id === "0"){
							that.setState({'output': lastOutput + '<i style="color:red">' + translation.import_posts_failed + '</i><br />' + translation.import_error + ' ' + resp['wp_ajax']['response']['import_posts_pages'].data
							});
						}else{
							that.setState({'output': lastOutput + '<i style="color:red">' + translation.import_posts_failed + '</i><br />' + translation.import_error + ' ' + resp['wp_ajax']['response']['import_posts_pages'].data });
						}
	
	
					} catch (error) {
						
						that.setState({'output': lastOutput + '<i style="color:red">' + v.import_posts_failed + ' ' + translation.import_try_reload + ' </i><br />' });
					}
	
				})
	
			}).then(() => new Promise(res => setTimeout(res, 3000)));


		
	}

	ajax_import_options() {
        //make the call for importing the widgets and the menus
		let that = this;
        const request = (
            url = this.ajaxUrl, 
            info = {
				method: "POST",
				mode: "cors",
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'
				},
				body: 'action=Iondigital_ajax_import_theme_options&_wpnonce='+ this.nonceImportThemeOptions,
				credentials: 'same-origin'
			}
        ) => fetch(url, info)


        const queueAjaxImportThemeOptions = new Queue([request]);

        const stream$ = this.Iterator(function* () {
            for(let q of queueAjaxImportThemeOptions){
				if( typeof q === 'function'){
					q = q();
				}

				yield q; // If this is a promise, the execution will wait until resolved to continue
            }
		});

		var decoder = new TextDecoder();
		
		return stream$(val => {

			if (val.status != 200 ){
				this.setState({output: this.state.output + '<i style="color:red">' + val.statusText + '</i><br/>' })
			}

			let reader = val.body.getReader();
			return reader.read().then(result => {

				let xml = decoder.decode(result.value || new Uint8Array, {
					stream: false
				});
				let resp = fromXML.fromXML( xml.substr(xml.indexOf('?>') + 2));
				try {
					
					let id = resp['wp_ajax']['response']['import_theme_options']['@id'];
					
					let lastOutput = that.state['output'];
					let translation = iondigital.importer.import_data;
					if(id === "1"){
						that.setState({'output': lastOutput + '<i>' + translation.import_theme_options_done + '</i><br />'
						+ '<i style="display: none;" > Response Data: ' + resp['wp_ajax']['response']['import_theme_options'].response_data + '</i><br />' });
					}else if(id === "0"){
						that.setState({'output': lastOutput + '<i style="color:red">' + translation.import_theme_options_error + ': '  + resp['wp_ajax']['response']['import_theme_options']['wp_error']['#'] + '<br/><br/>' 
						});
					}else{
						that.setState({'output': lastOutput + '<i style="color:red">' + translation.import_theme_options_failed + '</i><br />' });
					}


				} catch (error) {
					
					that.setState({'output': lastOutput + '<i style="color:red">' + translation.import_theme_options_failed + '</i><br />' });
				}

			})

		}).then(() => new Promise(res => setTimeout(res, 3000)));

		
	}

    ajax_widget(){
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
				body: 'action=Iondigital_ajax_import_widgets&_wpnonce='+ this.nonceImportWidgets,
				credentials: 'same-origin'
			}
        ) => fetch(url, info);


        const queueAjaxImportWidgets = new Queue([request]);

        const stream$ = this.Iterator(function* () {
            for(let q of queueAjaxImportWidgets){
				if( typeof q === 'function'){
					q = q();
				}
				
				yield q; //If this is a promise, the execution will wait until resolved to continue
				
            }
		});
		
		var decoder = new TextDecoder();

        return stream$(val => {

			if (val.status != 200 ){
				this.setState({output: this.state.output + '<i style="color:red">' + val.statusText + '</i><br/>' })
			}

			let reader = val.body.getReader();
			return reader.read().then(result => {

				let xml = decoder.decode(result.value || new Uint8Array, {
					stream: false
				});
				let resp = fromXML.fromXML( xml.substr(xml.indexOf('?>') + 2));
				try {
					
					let id = resp['wp_ajax']['response']['import_widgets']['@id'];
					
					let lastOutput = that.state['output'];
					let translation = iondigital.importer.import_data;

					if(id === "1"){
						that.setState({'output': lastOutput + '<i>' + translation.import_widgets_done + '</i><br />' +
						'<i style="display: none;" > Response Data: ' + resp['wp_ajax']['response']['import_widgets'].response_data + '</i><br />' });
					}else if(id === "0"){
						that.setState({'output': lastOutput + '<i style="color:red">' + translation.import_widgets_error + ': '  + resp['wp_ajax']['response']['import_widgets']['wp_error']['#'] + '<br/><br/>' 
						});
					}else{
						that.setState({'output': lastOutput + '<i style="color:red">' + translation.import_widgets_failed + '</i><br />' });
					}


				} catch (error) {
					
					that.setState({'output': lastOutput + '<i style="color:red">' + translation.import_widgets_failed + '</i><br />' });
				}

			})

		}).then(() => new Promise(res => setTimeout(res, 3000)));

	}

    async startImport(){

        var activate = confirm( 'Importing demo data will overwrite your current site content and options. Proceed anyway?' );

		if ( activate == false ) return false;
		
		this.setState({activateLoading: activate})
		try{
        	//queue the calls
			await this.ajax_widget();
			await this.ajax_import_options();
			await this.ajax_import_posts();

			this.setState({output: this.state.output + '<h3>' + iondigital.importer.import_data.import_phew + '</h3><br /><p>' + iondigital.importer.import_data.import_success_note + iondigital.importer.import_data.import_success_warning + '</p>' })

		}catch(err){
			this.setState({output: this.state.output + '<i style="color:red">' + err + '</i><br/>' })
		}
		


    }



    render() {
		let { output, activateLoading } = this.state; 
        return (
			<div className="Ion-importer">
				<button id="Ion-import-demodata-button" onClick={this.startImport}> Import demo data </button>
				{ activateLoading && <div className="Bitstarter-loading-wrap">
					<span className="Bitstarter-loading Bitstarter-import-loading"></span>
					<div className="Bitstarter-import-wait">Please wait a few minutes (between 1 and 3 minutes usually, but depending on your hosting it can take longer) and <strong>don't reload the page</strong>.You will be notified as soon as the import has finished!
					<br/>
					</div>
				</div>
				}
				<div dangerouslySetInnerHTML={{ __html: output }} />

			</div>
		)
    }

}
