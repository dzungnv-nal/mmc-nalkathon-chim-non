import React from 'react';

import {id as PluginId} from '../../manifest';

import ZohoChartUI from './ZohoChartUI';

const PLUGIN_API = 'https://mmc.docker.localhost/plugins';

class ZohoChart extends React.Component {
    constructor(props) {
        super(props);
        this.chartType = '';
    }
    shouldComponentUpdate() {
        return true;
    }
    async componentDidMount() {
        const response = await fetch(`${PLUGIN_API}/${PluginId}/get-configs`);
        const json = await response.json();
        this.chartType = json.ChartType;
    }
    render() {
        return (
            <ZohoChartUI/>
        );
    }
}
export default ZohoChart;
