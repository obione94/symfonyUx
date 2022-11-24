import { Controller } from 'stimulus';

export default class extends Controller {
    connect() {
        console.log('chart js');
    }

    onChartConnect(event) {
        this.chart = event.detail.chart;
        setTimeout(() => {
            this.setNewData();
        }, 3000)
    }
    setNewData() {
        this.chart.data.datasets[0].data[2] = 30;
        this.chart.update();
    }
}
