<template>
    <div class="vx-card p-6">
        <vs-popup classContent="popup-example"
                  :title="'Editar novedad'"
                  :active.sync="abrirPoput">
            <editar-novedad :novedad="infoNovedad"></editar-novedad>
        </vs-popup>

        <vs-row>
            <vs-col vs-sm="12" vs-md="4" vs-lg="4" vs-xl="4" class="mb-2 p-2">
                <strong>Gestor</strong>
                <vs-select autocomplete multiple class="selectExample w-full" v-model="filtro.gestor"
                           @change="filtroNovedades">
                    <vs-select-item :key="''" :value="''"
                                    :text="'---seleccione---'"/>
                    <vs-select-item :key="index" :value="item.id" class="disabledx"
                                    :text="item.text"
                                    v-for="(item,index) in listaGestores"/>
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
                <vs-button @click="filtroNovedades()"><i class="bx bx-search"></i></vs-button>
            </vs-col>
        </vs-row>
        <div class="flex flex-wrap items-center">
            <vs-input class="sm:mr-4 mr-0 sm:w-auto w-full sm:order-normal order-3 sm:mt-0 mt-4"
                      v-model="searchQuery" @input="updateSearchQuery" placeholder="Buscar..."/>
        </div>
        <ag-grid-vue style="width: 100%; height: 400px;"
                     ref="agGridTable"
                     :components="components"
                     :gridOptions="gridOptions"
                     class="ag-theme-material vs-table vs-table--tbody-table"
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
</template>

<script>
    import {AgGridVue} from "ag-grid-vue"
    import eliminarNovedad from '../../componentes/tabla/eliminarNovedad';
    import {DateTime} from 'luxon';
    import EditarNovedad from "./editarNovedad";

    export default {
        name: "listaNovedades",
        components: {EditarNovedad, AgGridVue, eliminarNovedad},
        data() {
            return {
                datos: [],
                searchQuery: "",
                filtro: {
                    gestor: '',
                    fechaInicio: null,
                    fechaFinal: null
                },
                listaGestores: [],
                infoNovedad: [],
                abrirPoput: false,
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
                        headerName: 'Tipo novedad',
                        field: 'tipoNovedad',
                    },
                    {
                        headerName: 'Gestor',
                        field: 'Gestor',
                    },
                    {
                        headerName: 'Fecha Inicio',
                        field: 'fechaInicio',
                    },
                    {
                        headerName: 'Fecha Fin',
                        field: 'fechaFin',
                    },
                    {
                        headerName: 'Horas',
                        field: 'horas',
                    },
                    {
                        headerName: 'Autoriza',
                        field: 'autoriza',
                    },
                    {
                        headerName: 'Observación',
                        field: 'observacion',
                        cellClass: "text-center",
                    },
                    {
                        headerName: 'Eliminar',
                        field: 'eliminar',
                        width: 90,
                        pinned: 'right',
                        cellClass: "text-center",
                        cellRendererFramework: 'eliminarNovedad'
                    }
                ],
                components: {eliminarNovedad}
            }
        },
        mounted() {
            this.gridApi = this.gridOptions.api
        },
        computed: {
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
        created() {
            this.listarNovedades();
        },
        methods: {
            listarNovedades() {
                this.cargandoGeneral = true;
                axios.post("/procesos/novedad.listarNovedades", this.filtro)
                    .then(res => {
                        this.datos = res.data.datos;
                        this.listaGestores = res.data.gestores;
                        this.filtro.fechaInicio = DateTime.fromSQL(res.data.fechaInicio);
                        this.filtro.fechaFinal = DateTime.fromSQL(res.data.fechaFinal);
                        this.cargandoGeneral = false;
                    })
            },
            filtroNovedades() {
                this.cargandoGeneral = true;
                axios.post("/procesos/novedad.listarNovedades", this.filtro)
                    .then(res => {
                        this.datos = res.data.datos;
                        this.cargandoGeneral = false;
                    })
            },
            updateSearchQuery(val) {
                this.gridApi.setQuickFilter(val)
            },
            infoEditNovedad(item) {
                this.infoNovedad = Object.assign({}, item);
                this.abrirPoput = true;
            },
            infoActualizada() {
                this.abrirPoput = false;
                this.listarNovedades();
            }
        },
        watch: {
            abrirPoput(val) {
                if (!val)
                    this.infoNovedad = [];
            }
        }
    }
</script>

<style scoped>

</style>
