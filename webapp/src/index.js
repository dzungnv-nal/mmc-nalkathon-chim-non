import {id as pluginId} from './manifest';

import ZohoChart from './components/zoho_chart';
import {ChartIcon} from './scss/icon';

export default class Plugin {
    // eslint-disable-next-line no-unused-vars
    initialize(registry, store) {
        registry.registerExtensionPanelComponent(ChartIcon, 'Todolist', ZohoChart);
    }
}

window.registerPlugin(pluginId, new Plugin());
