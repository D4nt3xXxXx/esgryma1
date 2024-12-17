<!-- =========================================================================================
    File Name: DashboardEcommerce.vue
    Description: Dashboard - Ecommerce
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
      Author: Pixinvent
    Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->

<template>
    <!--<div>

        <div class="vx-row">


             &lt;!&ndash; MIXED CHART &ndash;&gt;
            <div class="vx-col w-full md:w-2/3">
                <vx-card title="Historia mensual" code-toggler>
                    <vue-apex-charts type="line" height="350" :options="apexChatData.mixedChart.chartOptions" :series="apexChatData.mixedChart.series"></vue-apex-charts>
                    <template slot="codeContainer">
                        {{ apexChatData.mixedChartCode }}
                    </template>
                </vx-card>
            </div>

            &lt;!&ndash; RADIAL CHART &ndash;&gt;
            <div class="vx-col w-full md:w-1/3 mb-base">
                <vx-card title="Meta mes">
                    <template slot="actions">
                        <feather-icon icon="HelpCircleIcon" svgClasses="w-6 h-6 text-grey"></feather-icon>
                    </template>

                    &lt;!&ndash; CHART &ndash;&gt;
                    <template slot="no-body">
                        <div class="mt-10">
                            <vue-apex-charts type=radialBar height=240 :options="analyticsData.goalOverviewRadialBar.chartOptions" :series="goalOverview.series" />
                        </div>
                    </template>

                    &lt;!&ndash; DATA &ndash;&gt;
                    <div class="flex justify-between text-center mt-6" slot="no-body-bottom">
                        <div class="w-1/2 border border-solid d-theme-border-grey-light border-r-0 border-b-0 border-l-0">
                            <p class="mt-4">Meta</p>
                            <p class="mb-4 text-3xl font-semibold">$51,500,000</p>
                        </div>
                        <div class="w-1/2 border border-solid d-theme-border-grey-light border-r-0 border-b-0">
                            <p class="mt-4">Cumplimiento</p>
                            <p class="mb-4 text-3xl font-semibold">27,295,000</p>
                        </div>
                    </div>
                </vx-card>
            </div>
        </div>

        <div class="vx-row">

            &lt;!&ndash; RADIAL BAR CHART &ndash;&gt;
            <div class="vx-col md:w-1/3 w-full mb-base">
                <vx-card title="Reservas por estado" code-toggler>
                    <vue-apex-charts type="radialBar" height="350" :options="apexChatData.radialBarChart.chartOptions" :series="apexChatData.radialBarChart.series"></vue-apex-charts>
                    <template slot="codeContainer">
                        {{ apexChatData.radialBarChartCode }}
                    </template>
                </vx-card>
            </div>

            <div class="vx-col md:w-2/3 w-full mb-base">
                <echarts-pie-chart></echarts-pie-chart>
            </div>

        </div>

        <div class="vx-row">
            <div class="vx-col w-full">
                <echarts-bar-chart></echarts-bar-chart>
            </div>
        </div>

    </div>-->
    <dashboar-principal></dashboar-principal>
</template>

<script>
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
import DashboarPrincipal from "./view/dashboarPrincipal";

export default{
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
            apexChatData: apexChatData
        }
    },
    components: {
        DashboarPrincipal,
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
    },
    mounted() {
        this.$refs.chatLogPS.$el.scrollTop = this.$refs.chatLog.scrollHeight;
    },
    created() {
      // Subscribers gained - Statistics
      this.$http.get("/api/card/card-statistics/subscribers")
        .then((response) => { this.subscribersGained = response.data })
        .catch((error) => { console.log(error) })

      // Revenue Generated
      this.$http.get("/api/card/card-statistics/revenue")
        .then((response) => { this.revenueGenerated = response.data })
        .catch((error) => { console.log(error) })

      // Sales
      this.$http.get("/api/card/card-statistics/sales")
        .then((response) => { this.quarterlySales = response.data })
        .catch((error) => { console.log(error) })

      // Orders - Statistics
      this.$http.get("/api/card/card-statistics/orders")
        .then((response) => { this.ordersRecevied = response.data })
        .catch((error) => { console.log(error) })

      // Revenue Comparison
      this.$http.get("/api/card/card-analytics/revenue-comparison")
        .then((response) => { this.revenueComparisonLine = response.data })
        .catch((error) => { console.log(error) })

      // Goal Overview
      this.$http.get("/api/card/card-analytics/goal-overview")
        .then((response) => { this.goalOverview = response.data })
        .catch((error) => { console.log(error) })

      // Browser Analytics
      this.$http.get("/api/card/card-analytics/browser-analytics")
        .then((response) => { this.browserStatistics = response.data })
        .catch((error) => { console.log(error) })

      // Client Retention
      this.$http.get("/api/card/card-analytics/client-retention")
        .then((response) => { this.clientRetentionBar = response.data })
        .catch((error) => { console.log(error) })

      // Sessions By Device
      this.$http.get("/api/card/card-analytics/session-by-device")
        .then((response) => { this.sessionsData = response.data })
        .catch((error) => { console.log(error) })

      // Chat Log
      this.$http.get("/api/chat/demo-1/log")
        .then((response) => { this.chatLog = response.data })
        .catch((error) => { console.log(error) })

      // Customers
      this.$http.get("/api/card/card-analytics/customers")
        .then((response) => { this.customersData = response.data })
        .catch((error) => { console.log(error) })
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
