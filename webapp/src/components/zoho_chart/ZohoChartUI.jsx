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
    console.log('props.dataJson' ,props.dataJson)
    return (
        <div style={{ display: 'flex', justifyContent: 'center', alignItems: 'center' }}>
            <div>
                <div>
                    <h1 style={{color: '#e4167b'}} > Báo cáo dự án Khách hàng </h1>
                </div>
                <ChartConfig dataType='ordinal' dataJson={props.dataJson}>
                    {({ data }) => (
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
            </div>
        </div>
    );
};

export default ZohoChartUI;