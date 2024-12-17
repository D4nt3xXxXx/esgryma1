<!-- =========================================================================================
    File Name: TodoAddNew.vue
    Description: Add new todo component
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
      Author: Pixinvent
    Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
    <div class="todo__filters-container">

        <!-- all -->
        <div class="px-6 py-4">
            <div tag="span" class="flex cursor-pointer" :class="{'text-primary': todoFilter == 'all'}"
                 @click="consultaFiltro(0,'todo')">
                <feather-icon icon="LayersIcon"
                              :svgClasses="[{'text-primary stroke-current': todoFilter == 'all'}, 'h-6 w-6']"></feather-icon>
                <span class="text-lg ml-3">Todas</span>
            </div>
        </div>

        <vs-divider></vs-divider>

        <!-- starred -->
        <div class="px-6 py-4">
            <h5>Tipo</h5>

            <template v-for="filter in listaTipoActividad">
                <div tag="span" class="flex mt-6 cursor-pointer"
                     :class="{'text-primary': todoFilter == filter.text}"
                     @click="consultaFiltro(filter.id,'tipoActividad')"
                     :key="filter.filter">
                    <feather-icon icon="InfoIcon"
                                  :svgClasses="[{'text-primary stroke-current': todoFilter == filter.text}, 'h-6 w-6']"></feather-icon>
                    <span class="text-lg ml-3">{{ filter.text }}</span>
                </div>
            </template>

        </div>

        <vs-divider></vs-divider>

        <div class="px-6 py-4">
            <h5>Estado OT</h5>
            <div class="todo__lables-list">
                <div tag="span" class="todo__label flex items-center mt-6 cursor-pointer"
                     v-for="(tag, index) in listaEstadoOT" :key="index"
                     @click="consultaFiltro(tag.idEstado,'estadoOT')">
                    <div class="ml-1 h-3 w-3 rounded-full mr-4" :class="'border-2 border-solid border-'"/>
                    <span class="text-lg"
                          :class="{'text-primary': todoFilter == tag.nomEstado}">{{ tag.nomEstado }}</span>
                </div>
            </div>
        </div>

    </div>
</template>

<script>

    export default {
        data() {
            return {
                todoFilters: [
                    {
                        filterName: 'Capacitación y Entrenamiento',
                        filter: 'Capacitación y Entrenamiento',
                        icon: 'StarIcon'
                    },
                    {filterName: 'Asesoria y Consultoria', filter: 'Asesoria y Consultoria', icon: 'InfoIcon'},
                    {filterName: 'Centro de Entrenamiento', filter: 'Centro de Entrenamiento', icon: 'CheckIcon'},
                ],
                listaTipoActividad: [],
                listaEstadoOT: []
            }
        },
        computed: {
            taskTags() {
                return [{value: 'PROGRAMADA', text: 'PROGRAMADA'},
                    {value: 'REALIZADA - PEND INF', text: 'REALIZADA - PEND INF'},
                    {value: 'REALIZADA - PEND REV', text: 'REALIZADA - PEND REV'},
                    {value: 'EJECUTADA', text: 'EJECUTADA'},
                    {value: 'EJECUTADA - PEND INF', text: 'EJECUTADA - PEND INF'},]//this.$store.state.todo.taskTags;
            },
            todoFilter() {
                return this.$route.params.filter
            },
            baseUrl() {
                const path = this.$route.path
                return path.slice(0, path.lastIndexOf("/"))
            }
        },
        created() {
            this.consultarTipoActividad();
            this.consultarEstadoOT();
        },
        methods: {
            consultarTipoActividad() {
                axios.post("/ordenes/tipoActividad")
                    .then(res => {
                        this.listaTipoActividad = res.data;
                    })
            },
            consultarEstadoOT() {
                axios.post("/consultas/consultarEstadoOT")
                    .then(res => {
                        this.listaEstadoOT = res.data;
                    })
            },
            consultaFiltro(id, tipo) {
                this.$parent.$parent.$parent.consultarOTGestor(id, tipo);
            }
        }
    }
</script>
