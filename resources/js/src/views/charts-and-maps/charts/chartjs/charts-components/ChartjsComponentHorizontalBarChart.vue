<!-- =========================================================================================
	File Name: ChartjsComponentHorizontalBarChart.vue
	Description: Chartjs component - Horizontal Bar Chart
	----------------------------------------------------------------------------------------
	Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
	Author: Pixinvent
	Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<script>
    import {HorizontalBar, mixins} from 'vue-chartjs'

    const {reactiveProp} = mixins
    export default {
        extends: HorizontalBar,
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
            this.renderChart(this.data, this.options)
        }
    }
</script>
