<!-- =========================================================================================
  File Name: ordenes_gestion.vue
  Description: Lista de ordenes por gestión/gestionadas
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->

<template>

    <div id="page-user-list">

        <vx-card ref="filterCard" title="Filtros" class="user-list-filters mb-8" actionButtons
                 @refresh="resetColFilters" @remove="resetColFilters">
            <div class="vx-row">
                <div class="vx-col sm:12/12 md:w-12/12 lg:w-12/12">
                    <descargarExcel
                            class="vs-component vs-button vs-button-border vs-button-primary"
                            :fetch="descargarDatos"
                            name="datosReserva.xls">
                        Descargar datos
                    </descargarExcel>
                </div>
            </div>
            <div class="vx-row">
                <div class="vx-col ms:w-12/12 md:w-4/12 lg:w-4/12 w-full">
                    <label class="text-sm opacity-75">Estado</label>
                    <v-select :options="estadoOpciones" :clearable="false" :dir="$vs.rtl ? 'rtl' : 'ltr'"
                              v-model="estadoFiltro"/>
                </div>
                <div class="vx-col ms:w-12/12 md:w-4/12 lg:w-4/12 w-full">
                    <label class="text-sm opacity-75">Cliente</label>
                    <v-select :options="clienteOpciones" :clearable="false" :dir="$vs.rtl ? 'rtl' : 'ltr'"
                              v-model="clienteFiltro" class="mb-4 sm:mb-0"/>
                </div>
                <div class="vx-col ms:w-12/12 md:w-4/12 lg:w-4/12 w-full">
                    <label class="text-sm opacity-75">Empresa</label>
                    <v-select :options="empresasOpciones" :clearable="false" :dir="$vs.rtl ? 'rtl' : 'ltr'"
                              v-model="empresasFiltro"/>
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
                            <span class="mr-2">{{ currentPage * paginationPageSize - (paginationPageSize - 1) }} - {{ usersData.length - currentPage * paginationPageSize > 0 ? currentPage * paginationPageSize : usersData.length }} de {{ usersData.length }}</span>
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
                         :components="components"
                         :gridOptions="gridOptions"
                         class="ag-theme-material w-100 my-4 ag-grid-table"
                         :columnDefs="columnDefs"
                         :defaultColDef="defaultColDef"
                         :rowData="datos"
                         rowSelection="multiple"
                         colResizeDefault="shift"
                         :animateRows="true"
                         :floatingFilter="true"
                         :pagination="true"
                         :paginationPageSize="paginationPageSize"
                         :suppressPaginationPanel="true"
                         :enableRtl="$vs.rtl">
            </ag-grid-vue>

            <vs-pagination
                    :total="totalPages"
                    :max="7"
                    v-model="currentPage"/>

        </div>
    </div>

</template>

<script>
    import {AgGridVue} from "ag-grid-vue"
    import '@sass/vuexy/extraComponents/agGridStyleOverride.scss'
    import vSelect from 'vue-select'

    // Store Module
    import moduleUserManagement from '@/store/user-management/moduleUserManagement.js'

    // Cell Renderer
    import CeldaGestionarOrden from '../componentes/tabla/CeldaGestionarOrden'
    import descargarExcel from "vue-json-excel";

    var _vm = null;

    export default {
        components: {
            AgGridVue,
            vSelect,

            // Cell Renderer
            CeldaGestionarOrden,
            descargarExcel
        },
        data() {
            return {
                datos: [],
                // Filter Options
                tipoReservaFiltro: {},
                tipoReservaOpciones: [],
                clienteFiltro: {},
                clienteOpciones: [],
                estadoFiltro: {},
                estadoOpciones: [],
                asistenteFiltro: {},
                asistenteOpciones: [],
                empresasFiltro: {},
                empresasOpciones: [],
                searchQuery: "",
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
                        checkboxSelection: false,
                        headerCheckboxSelectionFilteredOnly: true,
                        headerCheckboxSelection: false,
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
                        headerName: 'Und',
                        field: 'unidadesOrden',
                        filter: true,
                        width: 120,
                        cellClass: "text-center",
                    },
                    {
                        headerName: 'Unidad Medida',
                        field: 'undMedidaOrden',
                        filter: true,
                        //cellRendererFramework: 'CellRendererVerified',
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
                        headerName: 'Estado O.T',
                        field: 'nomestadoot',
                        filter: true,
                    },
                    {
                        headerName: "Gestionar",
                        fiel: 'gestionar',
                        pinned: 'right',
                        width: 100,
                        cellClass: "text-center",
                        cellRendererFramework: 'CeldaGestionarOrden'
                    }
                ],

                // Cell Renderer Components
                components: {
                    CeldaGestionarOrden
                }
            }
        },
        watch: {
            tipoReservaFiltro(obj) {
                this.setColumnFilter("tipo_Reserva", obj.value)
            },
            clienteFiltro(obj) {
                this.setColumnFilter("nombreCliente", obj.value)
            },
            estadoFiltro(obj) {
                this.setColumnFilter("nomestadoot", obj.value)
            },
            empresasFiltro(obj) {
                this.setColumnFilter("empresaOrden", obj.value)
            },
        },
        computed: {
            usersData() {
                return this.$store.state.userManagement.users
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
                this.roleFilter = this.statusFilter = this.isVerifiedFilter = this.departmentFilter = {
                    label: 'All',
                    value: 'all'
                }

                this.$refs.filterCard.removeRefreshAnimation()
            },
            updateSearchQuery(val) {
                this.gridApi.setQuickFilter(val)
            },
            // listarReservas() {
            //     axios.post("/reservas/listarOrdenes")
            //         .then(res => {
            //             this.datos = res.data.datos;
            //             this.tipoReservaOpciones = res.data.filtroTipoReserva;
            //             this.clienteOpciones = res.data.filtroClientes;
            //             this.estadoOpciones = res.data.filtroEstados;
            //             this.asistenteOpciones = res.data.filtroAsistente;
            //         })
            // },

            listarOrdenes() {
                this.cargandoGeneral = true;
                axios.post("/ordenes/listarOrdenes", {idAsistente: 'Diana Alvarado'})
                    .then(res => {
                        this.datos = res.data.datos;
                        this.tipoReservaOpciones = res.data.filtroTipoReserva;
                        this.clienteOpciones = res.data.filtroClientes;
                        this.estadoOpciones = res.data.filtroEstados;
                        this.asistenteOpciones = res.data.filtroAsistente;
                        this.empresasOpciones = res.data.filtroEmpresas;
                        this.cargandoGeneral = false;
                    }, err => {
                        console.log(err);
                    })
            },
            async descargarDatos() {
                this.cargandoGeneral = true;
                const response = await axios.post("/ordenes/descargarDatos");
                console.log(response);
                this.cargandoGeneral = false;
                return response.data;
            },

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
        },
        created() {
            _vm = this;
            if (!moduleUserManagement.isRegistered) {
                this.$store.registerModule('userManagement', moduleUserManagement)
                moduleUserManagement.isRegistered = true
            }
            this.$store.dispatch("userManagement/fetchUsers").catch(err => {
                console.error(err)
            })
            this.listarOrdenes();
        }
    }

</script>

<style lang="scss">
    #page-user-list {
        .user-list-filters {
            .vs__actions {
                position: absolute;
                right: 0;
                top: 50%;
                transform: translateY(-58%);
            }
        }
    }
</style>

