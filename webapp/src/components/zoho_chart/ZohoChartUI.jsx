/* eslint-disable */
import React from 'react';
import {Chart} from 'react-charts';

import ChartConfig from '../ChartConfig';
const ZohoChartUI = (props) => {
    var seriesType = {type: 'bar'};
    var axes = [
        {primary: true, type: 'ordinal', position: 'bottom'},
        {position: 'left', type: 'linear', stacked: true},
    ];
    switch (props.chartType) {
    case 'Line_Chart':
        seriesType = {};
        axes = [
            {primary: true, position: 'bottom', type: 'ordinal'},
            {position: 'left', type: 'linear'},
        ];
        break;
    case 'Mixed_Types':
        seriesType = (s, i) => ({
            type: i % 2 ? 'bar' : 'line',
        });
        axes = [
            {primary: true, position: 'bottom', type: 'ordinal'},
            {position: 'left', type: 'linear', min: 0},
        ];
        break;
    default:
        break;
    }
    return (
        <ChartConfig dataType='ordinal'>
            {({data}) => (
                <Chart
                    data={data}
                    series={seriesType}
                    axes={axes}
                    primaryCursor={true}
                    secondaryCursor={true}
                    tooltip={true}
                />
            )}
        </ChartConfig>
    );
};

export default ZohoChartUI;