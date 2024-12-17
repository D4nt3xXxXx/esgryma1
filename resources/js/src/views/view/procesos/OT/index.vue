<!-- =========================================================================================
    File Name: Todo.vue
    Description: Todo Application to keep you ahead of time
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
      Author: Pixinvent
    Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
    <div id="todo-app" class="border border-solid d-theme-border-grey-light rounded relative overflow-hidden">
        <!--<notificacion></notificacion>-->
        <vs-sidebar class="items-no-padding" parent="#todo-app" :click-not-close="clickNotClose"
                    :hidden-background="clickNotClose" v-model="isSidebarActive">
            <todo-add-new/>
            <VuePerfectScrollbar class="todo-scroll-area" :settings="settings" :key="$vs.rtl">
                <todo-filters @closeSidebar="toggleTodoSidebar"></todo-filters>
            </VuePerfectScrollbar>
        </vs-sidebar>

        <div :class="{'sidebar-spacer': clickNotClose}"
             class="no-scroll-content border border-r-0 border-b-0 border-t-0 border-solid d-theme-border-grey-light no-scroll-content">
            <div
                class="flex d-theme-dark-bg items-center border border-l-0 border-r-0 border-t-0 border-solid d-theme-border-grey-light">

                <!-- TOGGLE SIDEBAR BUTTON -->
                <feather-icon class="md:inline-flex lg:hidden ml-4 mr-4 cursor-pointer" icon="MenuIcon"
                              @click.stop="toggleTodoSidebar(true)"/>

                <!-- SEARCH BAR -->
                <vs-input icon-no-border size="large" icon-pack="feather" icon="icon-search" placeholder="Buscar..."
                          v-model="buscar" @keydown="consultarOTGestor(buscar,'buscar')"
                          class="vs-input-no-border vs-input-no-shdow-focus w-full"/>
            </div>

            <!-- TODO LIST -->
            <VuePerfectScrollbar class="todo-content-scroll-area" :settings="settings" ref="taskListPS" :key="$vs.rtl">
                <transition-group class="todo-list" name="list-enter-up" tag="ul" appear>
                    <li class="cursor-pointer todo_todo-item" v-for="(task, index) in taskList"
                        :key="String(currFilter) + String(task.id)" :style="[{transitionDelay: (index * 0.1) + 's'}]">

                        <todo-task @verOrden="datosOrden" v-on:consultarFiltro="consultarOTGestor" :datos="task"
                                   :taskId="task.idOT"
                                   :key="String(task.actividad) + String(task.nomEstado)"/>
                        <!--
                          Note: Remove "todo-task" component's key just concat lastUpdated field in li key list.
                          e.g. <li class="..." v-for="..." :key="String(currFilter) + String(task.id) + String(task.lastUpdated)" .. >
                        -->
                    </li>
                </transition-group>
            </VuePerfectScrollbar>
            <!-- /TODO LIST -->
        </div>

        <vs-popup fullscreen classContent="popup-example"
                  :title="'Información orden de trabajo '"
                  :active.sync="abrirPoput">
            <ordenes-gestion-view @actualizarVistaGestor="changeVistaGestor" ref="componente_gestionar_orden"
                                  :id-reserva="idReserva"></ordenes-gestion-view>
        </vs-popup>
    </div>
</template>

<script>
    import moduleTodo from '@/store/todo/moduleTodo.js'
    import TodoAddNew from "../../../apps/todo/TodoAddNew.vue"
    import TodoTask from "../../../apps/todo/TodoTask.vue"
    import TodoFilters from "../../../apps/todo/TodoFilters.vue"
    import TodoEdit from "../../../apps/todo/TodoEdit.vue"
    import VuePerfectScrollbar from 'vue-perfect-scrollbar'
    import Notificacion from "../../componentes/notificaciones/notificacion";
    import ordenesGestionView from '../../../view/ordenes/ordenes_gestion_edit'

    export default {
        data() {
            return {
                currFilter: "",
                clickNotClose: true,
                displayPrompt: false,
                taskIdToEdit: 0,
                isSidebarActive: true,
                settings: {
                    maxScrollbarLength: 60,
                    wheelSpeed: 0.30,
                },
                buscar: '',
                datosOT: [],
                abrirPoput: false,
                idReserva: null
            }
        },
        watch: {
            todoFilter() {
                this.$refs.taskListPS.$el.scrollTop = 0
                this.toggleTodoSidebar()

                // Fetch Tasks
                let filter = this.$route.params.filter
                this.$store.dispatch("todo/fetchTasks", {filter: filter})
                this.$store.commit("todo/UPDATE_TODO_FILTER", filter)
            },
            windowWidth() {
                this.setSidebarWidth()
            },
        },
        computed: {
            todo() {
                return this.$store.state.todo.todoArray
            },
            todoFilter() {
                return this.$route.params.filter
            },
            taskList() {
                return this.datosOT//this.$store.getters["todo/queriedTasks"]
            },
            searchQuery: {
                get() {
                    this.consultarOTGestor('', "buscar")
                },
                set(val) {
                    //this.$store.dispatch('todo/setTodoSearchQuery', val)
                    this.consultarOTGestor(val, "buscar")
                }
            },
            windowWidth() {
                return this.$store.state.windowWidth
            }
        },
        methods: {
            showDisplayPrompt(itemId) {
                this.taskIdToEdit = itemId
                this.displayPrompt = true;
                console.log("llego")
            },
            hidePrompt() {
                this.displayPrompt = false
            },
            setSidebarWidth() {
                if (this.windowWidth < 992) {
                    this.isSidebarActive = this.clickNotClose = false
                } else {
                    this.isSidebarActive = this.clickNotClose = true
                }
            },
            toggleTodoSidebar(value = false) {
                if (!value && this.clickNotClose) return
                this.isSidebarActive = value
            },
            consultarOTGestor(id, tipo) {
                this.cargandoGeneral = true;
                axios.post("/gestor/listarOT", {"id": id, "tipo": tipo})
                    .then(res => {
                        this.cargandoGeneral = false;
                        this.datosOT = res.data;
                        this.$refs.taskListPS.$el.scrollTop = 0;
                    });
            },
            datosOrden(idReserva) {
                this.abrirPoput = true;
                this.idReserva = idReserva;
            },
            changeVistaGestor() {
                this.abrirPoput = false;
                this.consultarOTGestor(6, 'estadoOT')
            }
        },
        components: {
            Notificacion,
            TodoAddNew,
            TodoTask,
            TodoFilters,
            TodoEdit,
            VuePerfectScrollbar,
            ordenesGestionView
        },
        created() {
            this.consultarOTGestor('', '');
            this.$store.registerModule('todo', moduleTodo)
            this.setSidebarWidth()

            let filter = this.$route.params.filter

            // Fetch Tasks
            this.$store.dispatch("todo/fetchTasks", {filter: filter})
            this.$store.commit("todo/UPDATE_TODO_FILTER", filter)

            // Fetch Tags
            this.$store.dispatch("todo/fetchTags")
        },
        beforeUpdate() {
            this.currFilter = this.$route.params.filter
        },
        beforeDestroy: function () {
            this.$store.unregisterModule('todo')
        },
        mounted() {
            //this.$store.dispatch("todo/setTodoSearchQuery", "")
        }
    }

</script>


<style lang="scss">
    @import "@sass/vuexy/apps/todo.scss";
</style>
