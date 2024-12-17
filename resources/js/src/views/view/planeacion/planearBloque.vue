<template>
    <vx-card title="Planeacón en bloque">

        <p>A continuación seleccione los criterios para planear - asignar las reservas para un periodo especifico:
            <strong>
            </strong></p>

        <vx-card ref="filterCard" title="Filtros" class="user-list-filters mb-8" actionButtons
                 @refresh="resetColFilters" @remove="resetColFilters">
            <div class="vx-row">
                <div class="vx-col md:w-1/4 sm:w-1/2 w-full">
                    <label class="text-sm opacity-75">Tipo reserva</label>
                    <v-select :options="tipoReservaOpciones" :clearable="false" :dir="$vs.rtl ? 'rtl' : 'ltr'"
                              v-model="tipoReservaFiltro" class="mb-4 md:mb-0"/>
                </div>
                <div class="vx-col md:w-1/4 sm:w-1/2 w-full">
                    <label class="text-sm opacity-75">Cliente</label>
                    <v-select :options="clienteOpciones" :clearable="false" :dir="$vs.rtl ? 'rtl' : 'ltr'"
                              v-model="clienteFiltro" class="mb-4 sm:mb-0"/>
                </div>
                <div class="vx-col md:w-1/4 sm:w-1/2 w-full">
                    <label class="text-sm opacity-75">Mes</label>
                    <vs-select autocomplete class="selectExample w-full" v-model="filtroMes"
                               @change="consultarReservasSinPlan">
                        <vs-select-item :key="''" :value="''"
                                        :text="'---seleccione---'"/>
                        <vs-select-item :key="index" :value="index+1" class="disabledx"
                                        :text="item"
                                        v-for="(item,index) in meses"/>
                    </vs-select>
                </div>
            </div>
        </vx-card>
        <div class="vx-card p-6">

            <div class="flex flex-wrap items-center">

                <!-- ITEMS PER PAGE -->
                <div class="flex-grow">
                    <vs-dropdown vs-trigger-click class="cursor-pointer">
                        <div
                            class="p-4 border border-solid d-theme-border-grey-light rounded-full d-theme-dark-bg cursor-pointer flex items-center justify-between font-medium">
                            <span class="mr-2">{{
                                    currentPage * paginationPageSize - (paginationPageSize - 1)
                                }} - {{
                                    usersData.length - currentPage * paginationPageSize > 0 ? currentPage * paginationPageSize : usersData.length
                                }} of {{ usersData.length }}</span>
                            <feather-icon icon="ChevronDownIcon" svgClasses="h-4 w-4"/>
                        </div>
                        <!-- <vs-button class="btn-drop" type="line" color="primary" icon-pack="feather" icon="icon-chevron-down"></vs-button> -->
                        <vs-dropdown-menu>

                            <vs-dropdown-item @click="gridApi.paginationSetPageSize(10)">
                                <span>10</span>
                            </vs-dropdown-item>
                            <vs-dropdown-item @click="gridApi.paginationSetPageSize(20)">
                                <span>20</span>
                            </vs-dropdown-item>
                            <vs-dropdown-item @click="gridApi.paginationSetPageSize(25)">
                                <span>25</span>
                            </vs-dropdown-item>
                            <vs-dropdown-item @click="gridApi.paginationSetPageSize(30)">
                                <span>30</span>
                            </vs-dropdown-item>
                        </vs-dropdown-menu>
                    </vs-dropdown>
                </div>

                <!-- TABLE ACTION COL-2: SEARCH & EXPORT AS CSV -->
                <vs-input class="sm:mr-4 mr-0 sm:w-auto w-full sm:order-normal order-3 sm:mt-0 mt-4"
                          v-model="searchQuery" @input="updateSearchQuery" placeholder="Search..."/>
                <!-- <vs-button class="mb-4 md:mb-0" @click="gridApi.exportDataAsCsv()">Export as CSV</vs-button> -->
            </div>


            <!-- AgGrid Table -->
            <ag-grid-vue style="width: 100%; height: 400px;"
                         ref="agGridTable"
                         :gridOptions="gridOptions"
                         class="ag-theme-material w-100 my-4 ag-grid-table"
                         :columnDefs="columnDefs"
                         :defaultColDef="defaultColDef"
                         :rowData="datos"
                         rowSelection="multiple"
                         colResizeDefault="shift"
                         :animateRows="true"
                         :floatingFilter="false"
                         :pagination="true"
                         :paginationPageSize="paginationPageSize"
                         :suppressPaginationPanel="true"
                         :enableRtl="$vs.rtl"
                         :rowSelection="rowSelection"
                         @selection-changed="onSelectionChanged">
            </ag-grid-vue>

            <vs-pagination
                :total="totalPages"
                :max="7"
                v-model="currentPage"/>

        </div>
        <vx-card>
            <div class="vx-row">
                <div class="vx-col sm:w-12/12 md:w-3/12 lg:w-3/12 w-full mb-2">
                    <strong>TOTAL ORDENES:</strong>{{ totalOrdenes }}
                </div>
                <div class="vx-col sm:w-12/12 md:w-3/12 lg:w-3/12 w-full mb-2">
                    <strong>TOTAL MONTO:</strong>{{ convertirdecimal(totalMonto) }}
                </div>
                <div class="vx-col sm:w-12/12 md:w-2/12 lg:w-2/12 w-full mb-2">
                    <strong>Año:</strong>
                    <vs-select v-model="anio" @input="changeAnio"
                               class="w-full select-large">
                        <vs-select-item :key="index" :value="item.id" :text="item.text"
                                        v-for="(item,index) in listaAnio" class="w-full"/>
                    </vs-select>
                </div>
                <div class="vx-col sm:w-12/12 md:w-2/12 lg:w-2/12 w-full mb-2">
                    <strong>MES:</strong>
                    <vs-select v-model="mes" @input="consultarPlanPrevio"
                               class="w-full select-large">
                        <vs-select-item :key="index" :value="item.id" :text="item.text"
                                        v-for="(item,index) in listaMeses" class="w-full"/>
                    </vs-select>
                </div>
                <div class="vx-col sm:w-12/12 md:w-3/12 lg:w-3/12 w-full mb-2">
                    <vs-button @click="guardarPlan">
                        Planear
                    </vs-button>
                </div>
            </div>
        </vx-card>
    </vx-card>
</template>

<script>
import {AgGridVue} from "ag-grid-vue"
import vSelect from 'vue-select'

export default {
    name: "planearBloque",
    components: {
        AgGridVue,
        vSelect
    },
    data() {
        return {
            datos: [],
            tipoReservaFiltro: '',
            tipoReservaOpciones: [],
            clienteFiltro: '',
            clienteOpciones: [],
            totalOrdenes: 0,
            totalMonto: 0,
            anio: new Date().getFullYear(),
            listaAnio: [],
            mes: '',
            filtroMes: '',
            metaMes: null,
            metaOriginal: null,
            planPrevio: null,
            idPlan: null,
            meta: null,
            listaMeses: [],
            datosPlanFinal: [],
            validarGuardar: '',
            searchQuery: "",
            rowSelection: 'multiple',
            // AgGrid
            gridApi: null,
            gridOptions: {},
            defaultColDef: {
                sortable: true,
                resizable: true,
                suppressMenu: true
            },
            columnDefs: [
                {
                    headerName: 'No. Orden',
                    field: 'nroOrden',
                    filter: true,
                    checkboxSelection: true,
                    headerCheckboxSelectionFilteredOnly: true,
                    headerCheckboxSelection: true,
                },
                {
                    headerName: 'Tema',
                    field: 'temaOrden',
                    filter: true,
                },
                {
                    headerName: 'Empresa',
                    field: 'empresaOrden',
                    filter: true,
                },
                {
                    headerName: 'Valor Total',
                    field: 'valorOrdenTotal',
                    filter: true,
                    width: 120
                },
                {
                    headerName: 'Días vencimiento',
                    field: 'Dias_vencimiento',
                    filter: true,
                    width: 120
                },
                {
                    headerName: 'Horas',
                    field: 'unidadesOrden',
                    filter: true,
                    width: 120,
                    cellClass: "text-center",
                },
                {
                    headerName: 'Unidades',
                    field: 'unidades',
                    filter: true,
                    cellClass: "text-center"
                },
                {
                    headerName: 'Tipo',
                    field: 'tipo_Reserva',
                    filter: true,
                },
                {
                    headerName: 'Cliente',
                    field: 'nombreCliente',
                    filter: true,
                },
                {
                    headerName: 'Estado',
                    field: 'nomEstado',
                    filter: true,
                },
            ],
        }
    },
    computed: {
        usersData() {
            return this.datos;
        },
        paginationPageSize() {
            if (this.gridApi) return this.gridApi.paginationGetPageSize()
            else return 10
        },
        totalPages() {
            if (this.gridApi) return this.gridApi.paginationGetTotalPages()
            else return 0
        },
        currentPage: {
            get() {
                if (this.gridApi) return this.gridApi.paginationGetCurrentPage() + 1
                else return 1
            },
            set(val) {
                this.gridApi.paginationGoToPage(val - 1)
            }
        }
    },
    methods: {
        setColumnFilter(column, val) {
            const filter = this.gridApi.getFilterInstance(column)
            let modelObj = null

            if (val !== "all") {
                modelObj = {type: "equals", filter: val}
            }

            filter.setModel(modelObj)
            this.gridApi.onFilterChanged()
        },
        resetColFilters() {
            // Reset Grid Filter
            this.gridApi.setFilterModel(null)
            this.gridApi.onFilterChanged()

            // Reset Filter Options
            this.tipoReservaOpciones = this.clienteOpciones = {
                label: 'All',
                value: 'all'
            }

            this.$refs.filterCard.removeRefreshAnimation()
        },
        updateSearchQuery(val) {
            this.gridApi.setQuickFilter(val)
        },
        consultarReservasSinPlan() {
            this.cargandoGeneral = true;
            axios.post("/planificacion/consultarReservasSinPlan", {mes: this.filtroMes})
                .then(res => {
                    this.datos = res.data.datos;
                    this.tipoReservaOpciones = res.data.filtroTipoReserva;
                    this.clienteOpciones = res.data.filtroClientes;
                    this.cargandoGeneral = false;
                })
        },
        onSelectionChanged(event) {
            this.totalOrdenes = event.api.getSelectedNodes().length;
            var tempDatos = event.api.getSelectedNodes();
            var vm = this;
            this.datosPlanFinal = [];
            vm.totalMonto = 0;
            $.each(tempDatos, function (index, value) {
                vm.totalMonto += value.data.valorOrdenUnit;
                vm.datosPlanFinal.push(value.data);
            })
        },
        consultarPlanPrevio() {
            if ((this.anio != '' && this.anio != null) && (this.mes != '' && this.mes != null)) {
                this.cargandoGeneral = true;
                axios.post("/planificacion/consultarPlanPrevio", {anio: this.anio, mes: this.mes})
                    .then(res => {
                        if (typeof res.data != "string") {
                            console.log(res.data);
                            const datos = res.data;
                            this.metaMes = datos[0].meta;
                            this.metaOriginal = this.metaMes;

                            this.planPrevio = datos[2].planPrevio;
                            this.novedadesHora = parseInt(datos[3].horas);
                            this.idPlan = datos[4];

                            this.meta = this.metaMes - this.planPrevio;
                            this.validarGuardar = '';
                        } else {
                            this.mostrarNotificacion('', res.data, 'warning');
                            this.validarGuardar = res.data;
                        }

                        this.cargandoGeneral = false;
                    })
            } else {
                this.mostrarNotificacion('', "Debe seleccionar el mes para el plan!", 'warning');
            }
        },
        guardarPlan() {
            if (this.validarGuardar == '') {
                if (this.mes != '' && this.mes != null) {
                    if (this.totalMonto > 0) {
                        const datosPlan = [{
                            planMes: this.mes,
                            planAnio: this.anio,
                            criterio: 'DINERO',
                            monto: this.meta,
                            horasDisponibles: 0,
                            metaMes: this.metaMes,
                            porARL: 0,
                            porComercial: 0
                        }];
                        axios.post("/planificacion/guardarPlan", {
                            'datosPlan': datosPlan,
                            reservas: this.datosPlanFinal
                        })
                            .then(res => {
                                if (res.data == 'ok') {
                                    this.mostrarNotificacion(
                                        'Registro exitoso',
                                        'El plan se ha registrado con exito!',
                                        'success'
                                    );
                                    this.$router.push({name: 'planeacion.listaPlanes'});
                                } else {
                                    this.mostrarNotificacion(
                                        'Registro fallido',
                                        'Error::' + res.data,
                                        'error'
                                    )
                                }
                            })
                    } else {
                        this.mostrarNotificacion('', 'Lo sentimos, no ha seleccionado ninguna reserva!', 'warning');
                    }
                } else {
                    this.mostrarNotificacion('', 'Debe seleccionar el mes para el plan!', 'warning');
                }
            } else {
                this.mostrarNotificacion('', this.validarGuardar, 'warning');
            }
        },
        changeAnio() {
            var anioActual = new Date().getFullYear();
            if (anioActual != this.anio) {
                this.listaMeses = [];
                for (var j = 0; j < this.meses.length; j++) {
                    this.listaMeses.push({id: j + 1, text: this.meses[j]});
                }
            }
            this.consultarPlanPrevio();
        }
    },
    mounted() {
        this.gridApi = this.gridOptions.api

        /* =================================================================
          NOTE:
          Header is not aligned properly in RTL version of agGrid table.
          However, we given fix to this issue. If you want more robust solution please contact them at gitHub
        ================================================================= */
        if (this.$vs.rtl) {
            const header = this.$refs.agGridTable.$el.querySelector(".ag-header-container")
            header.style.left = "-" + String(Number(header.style.transform.slice(11, -3)) + 9) + "px"
        }
        this.$refs.filterCard.toggleContent();
        var anioActual = new Date().getFullYear();
        for (var i = anioActual; i <= (anioActual + 1); i++)
            this.listaAnio.push({id: i, text: i});
    },
    created() {
        var temp_mes = new Date().getMonth();
        for (var j = temp_mes; j < this.meses.length; j++) {
            this.listaMeses.push({id: j + 1, text: this.meses[j]});
        }
        this.filtroMes = (temp_mes + 1);
        this.consultarReservasSinPlan();
    },
    watch: {
        tipoReservaFiltro(obj) {
            this.setColumnFilter("tipo_Reserva", obj.value)
        },
        clienteFiltro(obj) {
            this.setColumnFilter("nombreCliente", obj.value)
        }
    }
}
</script>

<style scoped>

</style>
