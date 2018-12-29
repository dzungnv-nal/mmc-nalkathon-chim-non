/* eslint-disable */
import {id as pluginId} from './manifest';

import ZohoChart from './components/zoho_chart';
import {ChartIcon} from './scss/icon';

import {hot} from 'react-hot-loader';
import styled, {injectGlobal} from 'react-emotion';

import 'react-resizable/css/styles.css';
// import './scss/styles.css';
import Store from './stores/storeData'
const appStore = new Store();

injectGlobal`
  @import url('https://fonts.googleapis.com/css?family=Roboto+Mono');

  body {
    font-family: 'Roboto', sans-serif;
    font-weight: normal;
    font-size: 16px;
    margin: 0;
    padding: 0;
    line-height: 1.5;
    overflow-x: hidden;
  }
  * {
    box-sizing: border-box;
    -webkit-overflow-scrolling: touch;
  }
  #root {
    min-height: 100vh;
  }

  a {
    text-decoration: none;
    color: #108db8;
  }

  img {
    max-width: 100%;
  }

  .react-resizable {
    max-width: 100%;
  }

  .react-resizable-handle {
    bottom: -10px;
    right: -10px;
  }

  pre, code {
    font-family: 'Roboto Mono', monospace;
    user-select: text;
  }

  pre {
    font-size: 13px;
    border-radius: 5px;
  }
}
`

const AppStyles = styled('div')`
  min-height: 100vh;
`

const ZohoChartComponent = () =>{
    return (
        <AppStyles>
            <ZohoChart store = {appStore}/>
        </AppStyles>
    )
}

export default class Plugin {
    // eslint-disable-next-line no-unused-vars
    initialize(registry, store) {
        registry.registerExtensionPanelComponent(ChartIcon, 'Zoho Chart', hot(module)(ZohoChartComponent));
    }
    uninitialize() {
      // No clean up required.
  }
}

window.registerPlugin(pluginId, new Plugin());
