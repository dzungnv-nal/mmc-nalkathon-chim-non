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
    async getDataChart (){
        console.log('Refresh');
        const response = await fetch(this.props.store.settingConfig.ApiURL);
        const json = await response.json();
        this.props.store.dataChart(json);
    }
    handleOnRefresh = () =>{
        this.getDataChart();
    }
    render() {
        let settingConfig = this.props.store.settingConfig;
        let chartType = settingConfig === {} ? {}:settingConfig.ChartType
        return (
            <ZohoChartUI chartType={chartType} dataJson = {this.props.store.dataChart} onRefresh = {this.handleOnRefresh}/>
        );
    }
}
export default observer(ZohoChart);
