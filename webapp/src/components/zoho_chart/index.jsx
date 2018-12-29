/* eslint-disable */
import React from 'react';
import {observer} from 'mobx-react'

import {id as PluginId} from '../../manifest';

import ZohoChartUI from './ZohoChartUI';
class ZohoChart extends React.Component {
    constructor(props) {
        super(props);
        this.chartType = '';
        this.getConfigSeting();
    }
    shouldComponentUpdate() {
        return true;
    }

    async getConfigSeting() {
        const response = await fetch(`/plugins/${PluginId}/get-configs`);
        const json = await response.json();
        this.props.store.pushJSettingConfig(json);
    }
    render() {
        let settingConfig = this.props.store.settingConfig;
        let chartType = settingConfig === {} ? {}:settingConfig.ChartType
        return (
            <ZohoChartUI chartType={chartType} dataJson = {this.props.store.dataChart}/>
        );
    }
}
export default observer(ZohoChart);
