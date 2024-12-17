<template>
    <vs-row>
        <vs-card header="Categorias">
            <vs-row>
                <vs-col vs-sm="12" vs-md="4" vs-lg="4" vs-xl="4" class="mb-2 p-2">
                    <strong>Reporte</strong>
                    <vs-select autocomplete class="selectExample w-full" v-model="filtro.tipo" @change="changeReporte">
                        <vs-select-item :key="''" :value="''"
                                        :text="'---seleccione---'"/>
                        <vs-select-item :key="index" :value="item.id" class="disabledx"
                                        :text="item.categoria+' - '+item.nombreRep"
                                        v-for="(item,index) in datosTablaReporte"/>
                    </vs-select>
                </vs-col>
                <vs-col vs-sm="12" vs-md="3" vs-lg="3" vs-xl="3" class="mb-2 p-2">
                    <strong>Fecha desde</strong>
                    <datetime type="date"
                              input-class="vs-inputx vs-input--input normal"
                              value-zone="America/Bogota"
                              zone="America/Bogota" v-model="filtro.fechaInicio"
                              format="yyyy-MM-dd"
                              auto></datetime>
                </vs-col>
                <vs-col vs-sm="12" vs-md="3" vs-lg="3" vs-xl="3" class="mb-2 p-2">
                    <strong>Fecha hasta</strong>
                    <datetime type="date"
                              input-class="vs-inputx vs-input--input normal"
                              value-zone="America/Bogota"
                              zone="America/Bogota" v-model="filtro.fechaFinal"
                              format="yyyy-MM-dd"
                              auto></datetime>
                </vs-col>
                <vs-col vs-sm="12" vs-md="2" vs-lg="2" vs-xl="2" class="mb-2 p-2" vs-type="flex" vs-justify="center"
                        vs-align="center">
                    <vs-button @click="consultarReporte()"><i class="bx bx-search"></i></vs-button>
                </vs-col>
            </vs-row>
            <vs-row vs-type="flex" vs-justify="flex-end" v-show="datosTabla.length>0">
                <div class="vx-col sm:12/12 md:w-12/12 lg:w-12/12">
                    <descargarExcel
                        class="vs-component vs-button vs-button-border vs-button-primary"
                        :data="datosTabla"
                        :name="nombreArchivo">
                        Descargar datos
                    </descargarExcel>
                </div>
            </vs-row>
            <vs-row v-show="Object.keys(datosChart).length>0">
                <vs-col vs-sm="12" vs-md="12" vs-lg="12" vs-xl="12" class="mb-2 p-2">
                    <div id="chart">
                        <div v-if="Object.keys(reporteSeleccionado).length>0">
                            <div v-if="reporteSeleccionado.grafico==1">
                                <div v-show="reporteSeleccionado.tipo=='linea'">
                                    <chartjs-component-line-chart :chart-data="datosChart"
                                                                  :options="options"></chartjs-component-line-chart>
                                </div>
                                <div v-show="reporteSeleccionado.tipo=='barra'">
                                    <chartjs-component-bar-chart :chart-data="datosChart"
                                                                 :options="options"></chartjs-component-bar-chart>
                                    <!--<chartjs-component-horizontal-bar-chartt :chart-data="datosChart"
                                                                             :options="options"></chartjs-component-horizontal-bar-chart>-->
                                </div>
                            </div>
                        </div>

                    </div>
                </vs-col>
                <vs-col vs-sm="12" vs-md="12" vs-lg="12" vs-xl="12" class="mb-2" vs-type="flex" vs-justify="center"
                        vs-align="center">
                    <div class="btn-group">
                        <vs-button size="small">D</vs-button>
                        <vs-button size="small">S</vs-button>
                        <vs-button size="small">M</vs-button>
                    </div>
                </vs-col>
            </vs-row>
            <vs-row v-if="Object.keys(reporteSeleccionado).length>0">
                <vs-col vs-sm="12" vs-md="12" vs-lg="12" vs-xl="12" class="mb-2" vs-type="flex" vs-justify="center"
                        vs-align="center">
                    <div v-show="reporteSeleccionado.grafico==0">
                        <vue-pivottable-ui
                            class="w-full w-100"
                            :data="datosTabla"
                            aggregatorName='Sum'
                            :rendererName='reporteSeleccionado.tipo'
                            :rows="validarArray(reporteSeleccionado.ejeX)"
                            :cols="validarArray(reporteSeleccionado.ejeY)"
                            :vals="validarArray(reporteSeleccionado.series)"
                        >
                        </vue-pivottable-ui>
                    </div>
                </vs-col>
            </vs-row>
        </vs-card>
    </vs-row>
</template>

<script>
    import descargarExcel from "vue-json-excel";
    import {DateTime} from 'luxon';
    import ChartjsComponentLineChart
        from "../charts-and-maps/charts/chartjs/charts-components/ChartjsComponentLineChart";
    import ChartJSPluginDatalabels from "chartjs-plugin-datalabels";
    import ChartjsComponentBarChart from "../charts-and-maps/charts/chartjs/charts-components/ChartjsComponentBarChart";
    import ChartjsComponentHorizontalBarChart
        from "../charts-and-maps/charts/chartjs/charts-components/ChartjsComponentHorizontalBarChart";
    import {VuePivottable, VuePivottableUi} from 'vue-pivottable';
    import 'vue-pivottable/dist/vue-pivottable.css';
    import moment from 'moment';

    export default {
        name: "dashboarPrincipal",
        components: {
            ChartjsComponentHorizontalBarChart,
            ChartjsComponentBarChart, ChartjsComponentLineChart, descargarExcel,
            VuePivottable,
            VuePivottableUi
        },
        props: {
            categoria: {
                type: Array,
                required: true
            },
            subcategoria: {
                type: Array,
                required: true
            },
        },
        data() {
            return {
                filtro: {
                    tipo: 1,
                    fechaInicio: null,
                    fechaFinal: null
                },
                datosTablaReporte: [],
                listaTipoReporte: [],
                reporteSeleccionado: {},
                allReportes: [],
                seleccionFiltro: '',
                seleccionFiltro2: '',
                tabPrincipal: 0,
                tabSecundario: '',
                columnas: [],
                datosChart: {},
                options: {
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },
                    plugins: {
                        datalabels: {
                            formatter: function (value, context) {
                                return parseFloat(value).toLocaleString('de-DE');
                            }
                        }
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            },
                            gridLines: {
                                display: true
                            }
                        }],
                        xAxes: [{
                            ticks: {
                                beginAtZero: true
                            },
                            gridLines: {
                                display: false
                            }
                        }]
                    },
                    legend: {
                        display: true
                    },
                    tooltips: {
                        enabled: true,
                        mode: 'single',
                        callbacks: {
                            label: function (tooltipItems, data) {
                                return '$' + parseFloat(tooltipItems.yLabel).toLocaleString('de-DE');
                            }
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    height: 200
                },
                datosTabla: [],
                nombreReporte: ''
            }
        },
        computed: {
            nombreArchivo() {
                return this.nombreReporte + '_' + this.filtro.fechaInicio.substring(0, 10) + '_' + this.filtro.fechaFinal.substring(0, 10) + '.xls';
            }
        },
        created() {
            this.filtro.fechaInicio = DateTime.fromJSDate(moment().add(0, 'months').startOf('month').toDate()).toISODate();
            this.filtro.fechaFinal = DateTime.fromJSDate(moment().add(0, 'months').endOf('month').toDate()).toISODate();
            this.consultarTablaReportes();
        },
        methods: {
            changeReporte() {
                var tempDatos = this.datosTablaReporte.find(x => x.id == this.filtro.tipo);
                this.nombreReporte = tempDatos.nombreRep;
                this.consultarReporte();
            },
            consultarReporte() {
                if (this.filtro.tipo != '' && this.filtro.tipo != null) {
                    if (!this.isEmpty(this.filtro.fechaInicio) && !this.isEmpty(this.filtro.fechaFinal)) {
                        this.cargandoGeneral = true;
                        this.reporteSeleccionado = this.datosTablaReporte.find(x => x.id == this.filtro.tipo);
                        axios.post("/reportes/consultarReporte", {
                            consulta: this.reporteSeleccionado,
                            fechaInicio: this.filtro.fechaInicio,
                            fechaFin: this.filtro.fechaFinal
                        })
                            .then(res => {
                                var datos = res.data;
                                this.datosTabla = datos.datos;
                                this.datosChart = datos.datosChart.length > 0 ? datos.datosChart[0] : [];
                                this.armarTabla();
                                this.cargandoGeneral = false;
                            })
                    } else {
                        this.mostrarNotificacion("Filtro vacio", "La fecha de inicio o final no pueden estar vacios", "warning");
                    }
                } else {
                    this.mostrarNotificacion("Reporte vacio", "Debe seleccionar un reporte", "warning");
                }
            },
            armarTabla() {
                this.columnas = [];
                for (var key in this.datosTabla[0]) {
                    this.columnas.push(key);
                }
            },
            consultarTablaReportes() {
                this.cargandoGeneral = true;
                axios.post("/reportes/consultarTablaReportes", {
                    categoria: this.categoria,
                    subcategoria: this.subcategoria
                })
                    .then(res => {
                        this.cargandoGeneral = false;
                        var datos = res.data;
                        this.datosTablaReporte = datos.datos;
                        this.allReportes = datos.datos;
                        this.reporteSeleccionado = this.datosTablaReporte[0];
                        this.filtro.tipo = this.reporteSeleccionado.id;
                        this.nombreReporte = this.reporteSeleccionado.nombreRep;
                        if (this.datosTablaReporte.length > 0)
                            this.consultarReporte();
                    })
            },
            esNumero(val) {
                if (typeof val == 'number')
                    return this.convertirdecimal(val);

                return val;
            },
            validarArray(items) {
                var dato = items.split(',');
                var tempDato = [];
                $.each(dato, function (index, value) {
                    tempDato.push(value);
                })
                return tempDato;
            }
        },
    }
</script>

<style scoped>

</style>
