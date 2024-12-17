<!-- =========================================================================================
	File Name: ChartjsComponentLineChart.vue
	Description: Chartjs component - Line Chart
	----------------------------------------------------------------------------------------
	Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
	Author: Pixinvent
	Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<script>
    import {Line, mixins} from 'vue-chartjs'

    const {reactiveProp} = mixins
    export default {
        extends: Line,
        mixins: [reactiveProp],
        props: {
            chartData: {
                type: Object,
                default: null,
            },
            options: {
                type: Object,
                default: null,
            }
        },
        mounted() {
            this.renderChart(this.chartData, this.options)
        }
    }
</script>
