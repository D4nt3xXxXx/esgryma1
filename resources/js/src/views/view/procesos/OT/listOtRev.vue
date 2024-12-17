<template>
    <div id="page-user-list">
        <vs-popup fullscreen classContent="popup-example"
                  :title="'Información orden de trabajo '"
                  :active.sync="abrirPoput">
            <ordenes-gestion-view @changePoput="actualizarPoput" ref="componente_ver_ot"
                                  :id-reserva="idReserva"></ordenes-gestion-view>
        </vs-popup>
        <div class="vx-card p-6">
            <div class="vx-row">
                <div class="vx-col ms:w-12/12 md:w-6/12 lg:w-6/12 w-full">
                    <label class="text-sm opacity-75">Gestor</label>
                    <v-select :options="listaGestores" :clearable="false" :dir="$vs.rtl ? 'rtl' : 'ltr'"
                              v-model="gestor"/>
                </div>
                <div class="vx-col ms:w-12/12 md:w-6/12 lg:w-6/12 w-full">
                    <label class="text-sm opacity-75">Tipo de actividad</label>
                    <v-select :options="listaTipoActividad" :clearable="false" :dir="$vs.rtl ? 'rtl' : 'ltr'"
                              v-model="tipoActividad" class="mb-4 sm:mb-0"/>
                </div>
            </div>
            <div class="flex flex-wrap items-center">
                <!-- ITEMS PER PAGE -->
                <div class="flex-grow">
                    <vs-dropdown vs-trigger-click class="cursor-pointer">
                        <div
                            class="p-4 border border-solid d-theme-border-grey-light rounded-full d-theme-dark-bg cursor-pointer flex items-center justify-between font-medium">
                            <span class="mr-2">{{ currentPage * paginationPageSize - (paginationPageSize - 1) }} - {{ usersData.length - currentPage * paginationPageSize > 0 ? currentPage * paginationPageSize : usersData.length }} of {{ usersData.length }}</span>
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
                         :floatingFilter="false"
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
    import {AgGridVue} from 'ag-grid-vue'
    import ActualizarEstadoOT from '../../componentes/tabla/actualizarEstadoOT'
    import OrdenesGestionView from '../../ordenes/ordenes_gestion_edit'
    import vSelect from 'vue-select'


    export default {
        name: "listOtRev",
        components: {AgGridVue, ActualizarEstadoOT, OrdenesGestionView, vSelect},
        data() {
            return {
                datos: [],
                searchQuery: "",
                abrirPoput: false,
                idReserva: null,
                gestor: null,
                listaGestores: [],
                tipoActividad: null,
                listaTipoActividad: [],
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
                        headerName: 'ID',
                        field: 'idOT',
                        width: 90
                    },
                    {
                        headerName: 'No. Orden',
                        field: 'nroorden',
                    },
                    {
                        headerName: 'Actividad',
                        field: 'actividad',
                    },
                    {
                        headerName: 'Tipo actividad',
                        field: 'tipoactividad',
                    },
                    {
                        headerName: 'Gestor',
                        field: 'Gestor',
                    },
                    {
                        headerName: 'Fecha actividad',
                        field: 'fechaActividad',
                    },
                    {
                        headerName: 'Fecha fin actividad',
                        field: 'fechaFinActividad',
                    },
                    {
                        headerName: "Ver",
                        fiel: 'gestionar',
                        pinned: 'right',
                        width: 100,
                        cellClass: "text-center",
                        cellRendererFramework: 'ActualizarEstadoOT'
                    }
                ],

                // Cell Renderer Components
                components: {
                    ActualizarEstadoOT
                }
            }
        },
        computed: {
            usersData() {
                return this.datos
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
            this.consultarOtRevision();
        },
        methods: {
            updateSearchQuery(val) {
                this.gridApi.setQuickFilter(val)
            },
            consultarOtRevision() {
                this.cargandoGeneral = true;
                axios.post("/procesos/novedad.listarOtRev")
                    .then(res => {
                        this.datos = res.data.datos;
                        this.listaGestores = res.data.filtroGestores;
                        this.listaTipoActividad = res.data.filtroTipoActividad;
                        this.cargandoGeneral = false;
                    })
            },
            actualizarPoput(val, idReserva) {
                this.abrirPoput = val;
                this.idReserva = idReserva;
            },
            setColumnFilter(column, val) {
                const filter = this.gridApi.getFilterInstance(column)
                let modelObj = null

                if (val !== "all") {
                    modelObj = {type: "equals", filter: val}
                }

                filter.setModel(modelObj)
                this.gridApi.onFilterChanged()
            },
        },
        watch: {
            gestor(obj) {
                this.setColumnFilter("Gestor", obj.value)
            },
            tipoActividad(obj) {
                this.setColumnFilter("tipoactividad", obj.value)
            },
        }
    }
</script>

<style scoped>

</style>
