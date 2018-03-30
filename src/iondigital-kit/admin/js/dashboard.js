
// react and reactDom are global for speed up development
// fix for production needed

// import React from 'react';
// import ReactDOM from 'react-dom';

import Importer from './_importer.js';

class Dashboard extends React.Component {
  
    constructor(props){
        super(props)
    }

    render() {
        return (
                <div className="Ion-header" ><h1> { iondigital['themeSupports']['theme_name'] }</h1>
                <span>Version: { iondigital['themeSupports']['theme_version']}</span>
                <Importer/>
                </div>
        );
    }
}

ReactDOM.render(<Dashboard />, document.getElementById('iondigital-kit-dashboard'));
