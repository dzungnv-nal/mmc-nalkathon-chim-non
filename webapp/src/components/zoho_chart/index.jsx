import React from 'react';

import ZohoChartUI from './ZohoChartUI';

class ZohoChart extends React.Component {
    shouldComponentUpdate() {
        return true;
    }
    render() {
        return (
            <ZohoChartUI/>
        );
    }
}
export default ZohoChart;
