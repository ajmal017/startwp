
// react and reactDom are global for speed up development
// fix for production needed

// import React from 'react';
// import ReactDOM from 'react-dom';

import Importer from './_import.js';

class Dashboard extends React.Component {
  
    constructor(props){
        super(props);
        this.state = {tab: 1};
        this.changeTab = this.changeTab.bind(this)
    }

    changeTab(tab){
        return () => {
            this.setState({tab})
        }
    }

    render() {

        let {tab} = this.state;

        let isNeedToInstallPlugins = Object.values(iondigital['themeConfig']['pluginManager']['tgmpaPlugins']).filter((k)=>{
            return !k.is_active 
        })

        let installUrl = iondigital.adminUrl + 'themes.php?page=tgmpa-install-plugins&plugin_status=activate';

        let menu = Object.values(iondigital['dashboard']).map(o => o.title);

        return (
            <div>
                <div className="Ion-header" >
                    <h1> { iondigital.themeSupports['theme_name'] }</h1>
                    <span>Version: { iondigital.themeSupports['theme_version']}</span>
                </div>

                <div className="Ion-menu">
                    <ul>
                        {
                            menu.map( (title, idx) => {
                                return (
                                    <li key={`menu+${idx}`} className={`Ion-menu__item ${tab == idx + 1 ? 'Ion-menu__item--active' : ''}`} onClick={this.changeTab( idx + 1)} >{title}</li>
                                )
                            })
                        }
                    </ul>
                </div>
                <div >
                { tab == 1 && [ <div key="tab" className="Ion-tab"> 
                    

                    <h2 className="color-inblock shadow-inblock">{iondigital.dashboard.general["h2"]}</h2>

                    <p className="font-small">{iondigital.dashboard.general["text1"]}</p>
                    
                    <hr/>

                    <div className="f f-col"> <p> { iondigital.dashboard.general["text2"].split('%Theme%')[0] } { iondigital.themeSupports['theme_name']} { iondigital.dashboard.general["text2"].split('%Theme%')[1] } </p>
                    
                    <table className="Ion-plugins">
                        <thead>
                        <tr>
                            {Object.values(iondigital.dashboard.general["table"]).map((head, idx ) => {
                                return(
                                    <th key={`headedTaable-${idx}`}>{head}</th>
                                )
                            })}
                        </tr>
                        </thead>
                        <tbody>
                        { Object.values(iondigital['themeConfig']['pluginManager']['tgmpaPlugins']).map((k, i, a) => {
                            installUrl  = k.install_url;
                            return <tr key={i}>
                                    <td>{k.name}</td>
                                    <td>{k.active_version}</td>
                                    <td>{k.is_installed ? <img src={iondigital.assets['validate']} /> : ""}</td>
                                    <td>{k.is_active ? <img src={iondigital.assets['validate']} /> : ""}</td>
                                    <td>{k.is_up_to_date ? <img src={iondigital.assets['validate']} /> : ""}</td>
                                    <td>{k.required ? <img src={iondigital.assets['validate']} /> : ""}</td>
                                </tr>;
                        })}
                        </tbody>
                    </table>
        
                    { isNeedToInstallPlugins.length > 0 && <a href={ installUrl } className="Ion-plugins__all btn btn-primary">{iondigital.dashboard.general['btn_install']}</a>
                    }
                    </div>

                    <hr/>
            
                    <Importer/>
                    
                    </div>,
                    <div key="logo" className="Ion-powered"> <span> Powered by </span> <a target="_blank" href="http://iondigi.com"> <img src={iondigital.assets["logo"]} alt="ION" /></a> </div>,
                   <div key="versionplugin" className="Ion-plugin">Plugin Version: {iondigital['version']}</div>]
                   
                }

                { tab == 2 && <div key="tab" className="Ion-tab"> 
                    <h2 className="color-inblock shadow-inblock">{iondigital.dashboard.customizations["h2"]}</h2>
                    <p>{iondigital.dashboard.customizations["text1"]}
                    </p>
                    <a href={iondigital.dashboard.customizations["link"]} className="Ion-customize btn btn-primary">{iondigital.dashboard.customizations["btn"]}</a>
                </div> }

                { tab == 3 && <div key="tab" className="Ion-tab"> 
                    <h2 className="color-inblock shadow-inblock">{iondigital.dashboard.system["h2"]}</h2>
                    <table className="Ion__system">
                        <thead>
                        <tr>
                            <th><h3 style={{margin: 0}}>{iondigital.dashboard.system.table["t1"]}</h3></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        { Object.values(iondigital.systemData["installation"]).map((k, i, a) => {
                            return <tr key={i}>
                                    <td style={{fontWeight: 'bold', width: '40%'}} >{k.label}</td>
                                    <td>{k.value}</td>
                                    <td style={{width: '30%', textAlign: 'right'}} ></td>
                                </tr>;
                        })}
                        </tbody>
                    </table>
                    <br/>
                    <br/>
                    <table className="Ion__system">
                        <thead>
                        <tr>
                            <th><h3 style={{margin: 0}}>{iondigital.dashboard.system.table["t2"]}</h3></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        { Object.values(iondigital.systemData["system"]).map((k, i, a) => {
                            return <tr key={i}>
                                    <td style={{fontWeight: 'bold', width: '40%'}} >{k.label}</td>
                                    <td>{k.value}</td>
                                    <td style={{width: '30%', textAlign: 'right'}} >{
                                        a[i]['label'] == 'WP Version' && parseFloat(k.value) > parseFloat(k['min_wp_version']) && <p  style={{color: 'rgba(108, 69, 131, 0.8)'}} >Great!</p>
                                        }
                                        {
                                        a[i]['label'] == 'PHP Version' && parseFloat(k.value) > parseFloat(k['min_php_version']) && <p  style={{color: 'rgba(108, 69, 131, 0.8)'}} >Your PHP version is OK.</p>
                                        }
                                        {
                                        a[i]['label'] == 'MySQL Version' && parseFloat(k.value) > parseFloat(k['min_mysql_version']) && <p  style={{color: 'rgba(108, 69, 131, 0.8)'}} >Your MySQL version is OK.</p>
                                        }
                                        {
                                        a[i]['label'] == 'DB Charset' && k.value == 'utf8mb4' && <p  style={{color: 'rgba(108, 69, 131, 0.8)'}} >Go all out emoji-style!</p>
                                        }
                                    </td>
                                </tr>;
                        })}
                        </tbody>
                    </table>
                </div> }
                </div>
            </div>
            );

            
    }
}

ReactDOM.render(<Dashboard />, document.getElementById('iondigital-kit-dashboard'));
