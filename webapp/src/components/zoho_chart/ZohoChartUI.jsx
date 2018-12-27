import React from 'react';
import {Chart} from 'react-charts';

import ChartConfig from '../ChartConfig';

const ZohoChartUI = () => {
    return (
        <ChartConfig dataType='ordinal'>
            {({data}) => (
                <Chart
                    data={data}
                    series={{type: 'bar'}}
                    axes={[
                        {primary: true, type: 'ordinal', position: 'bottom'},
                        {position: 'left', type: 'linear', stacked: true},
                    ]}
                    primaryCursor={true}
                    secondaryCursor={true}
                    tooltip={true}
                />
            )}
        </ChartConfig>
    );
};

export default ZohoChartUI;