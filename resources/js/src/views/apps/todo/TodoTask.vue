<!-- =========================================================================================
    File Name: TodoItem.vue
    Description: Single todo item component
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
      Author: Pixinvent
    Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
    <div @click="displayPrompt" class="px-4 py-4 list-item-component">
        <div class="vx-row">
            <div class="vx-col w-full sm:w-5/6 flex sm:items-center sm:flex-row flex-col">
                <div class="flex items-center">
                    <!--<vs-checkbox v-model="isCompleted" class="w-8 m-0 vs-checkbox-small" @click.stop/>-->
                    <h6 class="todo-title" :class="{'line-through': taskLocal.isCompleted}">
                        {{taskLocal.nroorden+' - '+taskLocal.actividad }}
                    </h6>
                </div>
                <div class="todo-tags sm:ml-2 sm:my-0 my-2 flex">
                    <vs-chip>
                        <div class="h-2 w-2 rounded-full mr-1" :class="'bg-primary'"></div>
                        <span>{{ taskLocal.nomEstado | capitalize }}</span>
                    </vs-chip>
                </div>
            </div>

            <div class="vx-col w-full sm:w-1/6 ml-auto flex sm:justify-end">
                <vs-avatar @click.stop="toggleIsImportant(taskLocal.idReserva)" transparent icon="remove_red_eye"/>
                <vs-avatar @click.stop="actualizarEstado(taskLocal.idOT,taskLocal.llegada,'llegada',taskLocal)"
                           transparent
                           :color="taskLocal.llegada==null || taskLocal.llegada=='' ? 'dark' : 'success'"
                           icon="my_location"/>
                <vs-avatar @click.stop="actualizarEstado(taskLocal.idOT,taskLocal.llamada,'llamada',taskLocal)"
                           transparent
                           :color="taskLocal.llamada==null || taskLocal.llamada=='' ? 'dark' : 'success'" icon="call"/>
            </div>
        </div>
        <div class="vx-row" v-if="taskLocal.tipoActividad">
            <div class="vx-col w-full">
                <p class="mt-2 truncate" :class="{'line-through': taskLocal.isCompleted}">
                    {{ taskLocal.fechaActividad }} - {{ taskLocal.tipoActividad }} - {{taskLocal.empresaOrden}}</p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            taskId: {
                type: Number,
                required: true,
            },
            datos: {
                type: [Array, Object],
                required: true
            }
        },
        data() {
            return {
                taskLocal: this.datos,//this.$store.getters["todo/getTask"](this.taskId)
                abrirPoput: false,
                idReserva: null
            }
        },
        computed: {
            isCompleted: {
                get() {
                    return true//this.taskLocal.isCompleted
                },
                set(value) {
                    this.$store.dispatch("todo/updateTask", Object.assign({}, this.taskLocal, {isCompleted: value}))
                        .then((response) => {
                            this.taskLocal.isCompleted = response.data.isCompleted
                        })
                        .catch((error) => {
                            console.error(error)
                        })
                }
            },
            todoLabelColor() {
                return (label) => {
                    const tags = this.$store.state.todo.taskTags
                    return tags.find((tag) => {
                        return tag.value == label
                    }).color
                }
            }
        },
        methods: {
            toggleIsImportant(id) {
                /*this.$store.dispatch("todo/updateTask", Object.assign({}, this.taskLocal, {isImportant: !this.taskLocal.isImportant}))
                    .then((response) => {
                        this.taskLocal.isImportant = response.data.isImportant
                    })
                    .catch((error) => {
                        console.error(error)
                    })*/
                /*this.idReserva = id;
                this.$refs.componente_gestionar_orden.obtenerreservaId(id);
                this.abrirPoput = true;*/
                this.$emit("verOrden", id);
            },
            toggleIsStarred() {
                this.$store.dispatch("todo/updateTask", Object.assign({}, this.taskLocal, {isStarred: !this.taskLocal.isStarred}))
                    .then((response) => {
                        this.taskLocal.isStarred = response.data.isStarred
                    })
                    .catch((error) => {
                        console.error(error)
                    })
            },
            moveToTrash() {

                this.$store.dispatch("todo/updateTask", Object.assign({}, this.taskLocal, {isTrashed: true}))
                    .then((response) => {
                        // console.log(response.data);
                        this.taskLocal.isTrashed = response.data.isTrashed
                        this.$el.style.display = "none"   // Hides element from DOM
                    })
                    .catch((error) => {
                        console.error(error)
                    })

                // Un-comment below line if you want to fetch task after task is deleted
                // this.$store.dispatch("todo/fetchTasks", {filter: this.$route.params.filter})
            },
            displayPrompt() {
                this.$emit('showDisplayPrompt', this.taskId)
            },
            actualizarEstado(idOT, valor, opcion, datosOT) {
                valor = (valor != null && valor != '' ? false : true);
                var mensaje = opcion == "llamada" ? 'llamada al cliente' : 'llegada al sitio'
                this.$swal.fire({
                    title: 'Guardar ' + mensaje,
                    text: "NOTA: una vez guardada la información se notificara al asistente encargado.",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, guardar!'
                }).then((result) => {
                    if (result.value) {
                        axios.post("/gestor/actualizarEstados", {
                            "idOT": idOT,
                            "valor": valor,
                            "opcion": opcion,
                            "datosOT": datosOT
                        })
                            .then(res => {
                                var mensaje = '';
                                if (opcion == 'llamada') {
                                    mensaje = "Se agrego tu llamada al cliente con exito!";
                                    this.taskLocal.llamada = res.data;
                                } else {
                                    mensaje = "Se agrego tu ubicación con exito!";
                                    this.taskLocal.llegada = res.data;
                                }
                                this.mostrarNotificacion("Actualización exitosa", mensaje, 'success');
                            })
                    }
                })

            }
        },
    }
</script>
