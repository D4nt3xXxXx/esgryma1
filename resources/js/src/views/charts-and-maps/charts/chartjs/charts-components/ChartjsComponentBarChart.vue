<!-- =========================================================================================
	File Name: ChartjsComponentBarChart.vue
	Description: Chartjs component - Bar Chart
	----------------------------------------------------------------------------------------
	Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
	Author: Pixinvent
	Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<script>
    import {Bar, mixins} from 'vue-chartjs'

    const {reactiveProp} = mixins
    export default {
        extends: Bar,
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
