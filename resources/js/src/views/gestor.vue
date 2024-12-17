<template>
    <div>
        <div class="vx-row">
            <div class="vx-col w-full md:w-2/3">
                <vx-card title="Planeado vs Facturado meta mes">
                    <vue-apex-charts ref="chart1" type="line" height="350" :options="chartOptions"
                                     :series="seriesGrafico1"></vue-apex-charts>
                </vx-card>
            </div>
            <!-- RADIAL CHART -->
            <div class="vx-col w-full md:w-1/3 mb-base">
                <vx-card :title="'Cumplimiento mes de '+mesSeleccionado">
                    <template slot="actions">
                        <feather-icon icon="HelpCircleIcon" svgClasses="w-6 h-6 text-grey"></feather-icon>
                    </template>
                    <!-- CHART -->
                    <template slot="no-body">
                        <div class="mt-10">
                            <vue-apex-charts type="radialBar" height="240"
                                             :options="analyticsData.goalOverviewRadialBar.chartOptions"
                                             :series="serieGrafico2"/>
                        </div>
                    </template>
                    <!-- DATA -->
                    <div class="flex justify-between text-center mt-6" slot="no-body-bottom"
                         v-if="Object.keys(datosGrafico2).length>0">
                        <div
                            class="w-1/2 border border-solid d-theme-border-grey-light border-r-0 border-b-0 border-l-0">
                            <p class="mt-4">Meta</p>
                            <p class="mb-4 text-3xl font-semibold">${{convertirdecimal(datosGrafico2.meta)}}</p>
                        </div>
                        <div class="w-1/2 border border-solid d-theme-border-grey-light border-r-0 border-b-0">
                            <p class="mt-4">Cumplimiento</p>
                            <p class="mb-4 text-3xl font-semibold">${{convertirdecimal(datosGrafico2.Total_fact)}}</p>
                        </div>
                    </div>
                </vx-card>
            </div>
        </div>
        <div class="vx-row">
            <!-- RADIAL BAR CHART -->
            <div class="vx-col md:w-1/3 w-full mb-base">
                <vx-card title="Total reservas por tipo" code-toggler>
                    <vue-apex-charts ref="chart3" type="radialBar" height="390" :options="chartOptions3"
                                     :series="serieGrafico3"></vue-apex-charts>
                </vx-card>
            </div>
            <div class="vx-col md:w-2/3 w-full mb-base">
                <vx-card title="Total reservas por cliente" code-toggler>
                    <e-charts autoresize :options="pie" ref="pie" auto-resize/>
                </vx-card>
            </div>
        </div>
    </div>
</template>

<script>
    const themeColors = ['#7367F0', '#28C76F', '#EA5455', '#FF9F43', '#1E1E1E']
    import VuePerfectScrollbar from 'vue-perfect-scrollbar'
    import VueApexCharts from 'vue-apexcharts'
    import apexChatData from './charts-and-maps/charts/apex-charts/apexChartData'
    import StatisticsCardLine from '@/components/statistics-cards/StatisticsCardLine.vue'
    import analyticsData from './ui-elements/card/analyticsData.js'
    import ChangeTimeDurationDropdown from '@/components/ChangeTimeDurationDropdown.vue'

    import EchartsBarChart from './charts-and-maps/charts/echarts/EchartsBarChart.vue'
    import EchartsLineChart from './charts-and-maps/charts/echarts/EchartsLineChart.vue'
    import EchartsPieChart from './charts-and-maps/charts/echarts/EchartsPieChart.vue'
    import EchartsPolarChart from './charts-and-maps/charts/echarts/EchartsPolarChart.vue'
    import EchartsScatterChart from './charts-and-maps/charts/echarts/EchartsScatterChart.vue'
    import EchartsRadarChart from './charts-and-maps/charts/echarts/EchartsRadarChart.vue'

    import ECharts from 'vue-echarts/components/ECharts'
    import 'echarts/lib/component/tooltip'
    import 'echarts/lib/component/legend'
    import 'echarts/lib/chart/pie'

    export default {
        data() {
            return {
                subscribersGained: {},
                revenueGenerated: {},
                quarterlySales: {},
                ordersRecevied: {},

                revenueComparisonLine: {},
                goalOverview: {},

                browserStatistics: [],
                clientRetentionBar: {},

                sessionsData: {},
                chatLog: [],
                chatMsgInput: '',
                customersData: {},

                analyticsData: analyticsData,
                settings: { // perfectscrollbar settings
                    maxScrollbarLength: 60,
                    wheelSpeed: .60,
                },
                apexChatData: apexChatData,
                chartOptions: {
                    colors: themeColors,
                    chart: {
                        stacked: false,
                    },
                    stroke: {
                        width: [0, 2, 5],
                        curve: 'smooth'
                    },
                    plotOptions: {
                        bar: {
                            columnWidth: '50%'
                        }
                    },

                    fill: {
                        opacity: [0.85, 0.25, 1],
                        gradient: {
                            inverseColors: false,
                            shade: 'light',
                            type: "vertical",
                            opacityFrom: 0.85,
                            opacityTo: 0.55,
                            stops: [0, 100, 100, 100]
                        }
                    },
                    /*labels: [],*/
                    markers: {
                        size: 0
                    },
                    xaxis: {
                        /*type: 'datetime',*/
                        title: {
                            text: 'Mes',
                        },
                        categories: []
                    },
                    yaxis: {
                        title: {
                            text: '$',
                        },
                        min: 0
                    },
                    tooltip: {
                        shared: true,
                        intersect: false,
                        y: {
                            formatter: function (y) {
                                if (typeof y !== "undefined") {
                                    return "$ " + parseFloat(y).toLocaleString('de-DE');
                                }
                                return y;
                            }
                        }
                    }
                },
                chartOptions3: {
                    chart: {
                        height: 390,
                        type: 'radialBar',
                    },
                    plotOptions: {
                        radialBar: {
                            offsetY: 0,
                            startAngle: 0,
                            endAngle: 270,
                            hollow: {
                                margin: 5,
                                size: '30%',
                                background: 'transparent',
                                image: undefined,
                            },
                            dataLabels: {
                                name: {
                                    show: true,
                                },
                                value: {
                                    formatter: function (val) {
                                        return "$ " + parseFloat(val).toLocaleString('de-DE');
                                    },
                                    color: '#111',
                                    fontSize: '14px',
                                    show: true,
                                },
                                total: {
                                    show: true,
                                    label: '',
                                    formatter: function (w, s, a) {
                                        var total = 0;
                                        $.each(w.config.series, function (index, value) {
                                            total += value;
                                        })
                                        return "$ " + parseFloat(total).toLocaleString('de-DE');
                                    }
                                }
                            }
                        }
                    },
                    colors: themeColors,
                    labels: [],
                    legend: {
                        show: true,
                        floating: true,
                        fontSize: '10px',
                        position: 'left',
                        offsetX: 0,
                        offsetY: 15,
                        labels: {
                            useSeriesColors: true,
                        },
                        markers: {
                            size: 0
                        },
                        formatter: function (seriesName, opts) {
                            return seriesName + ":  " + parseFloat(opts.w.globals.series[opts.seriesIndex]).toLocaleString('de-DE')
                        },
                        itemMargin: {
                            vertical: 1
                        }
                    },
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            legend: {
                                show: false
                            }
                        }
                    }]
                },
                pie: {
                    tooltip: {
                        trigger: 'item',
                        formatter(param) {
                            return (
                                param.data.name + ': ' + parseFloat(param.data.value).toLocaleString('de-DE') + " " + param.percent + " %"
                            )
                        }/*'{a} <br/>{b} : {c} ({d}%)'*/
                    },
                    legend: {
                        right: 'left',
                        orient: 'horizontal',
                        padding: [
                            5,  // up
                            10, // right
                            5,  // down
                            10, // left
                        ],
                        formatter(val) {
                            return val.length > 10 ? val.substring(0, 10) + '...' : val;
                        },
                        data: []
                    },
                    toolbox: {
                        show: true,
                        feature: {
                            mark: {show: true},
                            dataView: {show: true, readOnly: false},
                            magicType: {
                                show: true,
                                type: ['pie', 'funnel']
                            },
                            restore: {show: true},
                            saveAsImage: {show: true}
                        }
                    },
                    calculable: true,
                    series: [{
                        name: 'Access source',
                        type: 'pie',
                        radius: '50%',
                        center: ['50%', '65%'],
                        color: ['#FF9F43', '#28C76F', '#EA5455', '#87ceeb', '#7367F0'],
                        data: [],
                        itemStyle: {
                            emphasis: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        }
                    }]
                },
                seriesGrafico1: [],
                datosGrafico2: [],
                serieGrafico2: [],
                serieGrafico3: [],
                labels4: [],
                seriesChart4: [],
                mesSeleccionado: ''
            }
        },
        components: {
            VueApexCharts,
            StatisticsCardLine,
            VuePerfectScrollbar,
            ChangeTimeDurationDropdown,

            EchartsBarChart,
            EchartsLineChart,
            EchartsPieChart,
            EchartsPolarChart,
            EchartsScatterChart,
            EchartsRadarChart,
            ECharts
        },
        mounted() {
            this.$refs.chatLogPS.$el.scrollTop = this.$refs.chatLog.scrollHeight;
        },
        created() {
            this.consultarDatosMeta("grafico1");
            this.consultarDatosMeta("grafico2");
            this.consultarDatosMeta("grafico3");
            this.consultarDatosMeta("grafico4");

            var mes = new Date().getMonth();
            this.mesSeleccionado = this.meses[mes];
        },
        methods: {
            consultarDatosMeta(opcion) {
                axios.post("/procesos/consultarMetaFacturado", {"opcion": opcion})
                    .then(res => {
                        if (opcion == "grafico1") {
                            this.seriesGrafico1 = res.data[2];
                            this.chartOptions = {
                                xaxis: {categories: res.data[1]}
                            };
                            // this.$refs.chart1.updateOptions({labels: res.data[1]})
                        } else if (opcion == "grafico2") {
                            this.datosGrafico2 = res.data[0];
                            this.serieGrafico2 = [];
                            this.serieGrafico2.push(parseFloat((this.datosGrafico2.Total_fact / this.datosGrafico2.meta)*100).toFixed(2));
                        } else if (opcion == "grafico3") {
                            var colores = [];
                            var vm = this;
                            $.each(res.data.labels, function (index, value) {
                                colores.push(vm.getRandomColor());
                            })
                            this.chartOptions3.labels = res.data.labels;
                            this.serieGrafico3 = res.data.series;
                            this.chartOptions3.colors = colores;
                            this.$refs.chart3.updateOptions({labels: res.data.labels})
                        } else if (opcion == "grafico4") {
                            var colores = [];
                            var vm = this;
                            $.each(res.data.labels, function (index, value) {
                                colores.push(vm.getRandomColor());
                            })
                            this.pie.legend.data = res.data.labels;
                            this.pie.series[0].data = res.data.series;
                            this.pie.series[0].color = colores;

                            let dataIndex = -1
                            let pie = this.$refs.pie
                            let dataLen = pie.options.series[0].data.length
                            setInterval(() => {
                                pie.dispatchAction({
                                    type: 'downplay',
                                    seriesIndex: 0,
                                    dataIndex
                                })
                                dataIndex = (dataIndex + 1) % dataLen
                                pie.dispatchAction({
                                    type: 'highlight',
                                    seriesIndex: 0,
                                    dataIndex
                                })
                                pie.dispatchAction({
                                    type: 'showTip',
                                    seriesIndex: 0,
                                    dataIndex
                                })
                            }, 1000)
                        }
                    })
            },
            getRandomColor() {
                var letters = '0123456789ABCDEF';
                var color = '#';
                for (var i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                return color;
            }
        }
    }
</script>

<style lang="scss">
    .chat-card-log {
        height: 400px;

        .chat-sent-msg {
            background-color: #f2f4f7 !important;
        }
    }
</style>
