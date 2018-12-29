/* eslint-disable */
import {decorate, observable, configure, action} from 'mobx';

configure({enforceActions: 'observed'});

class StoreData {
  settingConfig = {};
  dataChart =[{"label":"Tổng số khách hàng hiện có","datums":[{"x":"Jan","y":14},{"x":"Feb","y":14},{"x":"March","y":15},{"x":"April","y":14},{"x":"May","y":20},{"x":"June","y":35},{"x":"July","y":6}]},{"label":"Số dự án mới","datums":[{"x":"Jan","y":5},{"x":"Feb","y":3},{"x":"March","y":5},{"x":"April","y":6},{"x":"May","y":6},{"x":"June","y":6},{"x":"July","y":9}]},{"label":"Số dự án kết thúc","datums":[{"x":"Jan","y":5},{"x":"Feb","y":8},{"x":"March","y":7},{"x":"April","y":4},{"x":"May","y":7},{"x":"June","y":10},{"x":"July","y":15}]},{"label":"Tổng số dự án hiện có","datums":[{"x":"Jan","y":20},{"x":"Feb","y":15},{"x":"March","y":13},{"x":"April","y":15},{"x":"May","y":20},{"x":"June","y":11},{"x":"July","y":23}]}];

  clearStoreData() {
      this.settingConfig = {};
      this.dataChart = [];
  }

  pushJSettingConfig(e) {
      this.settingConfig = e;
  }
  pushDataChart(e) {
      this.dataChart = e;
  }
}

decorate(StoreData, {
    settingConfig: observable,
    clearStoreData: action,
    pushJSettingConfig: action,
    pushDataChart: action,
});

export default StoreData;