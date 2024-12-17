<template>
    <div class="flex items-center">
        <a @click.prevent="abrirPoputView(params.data.idReserva)">
            <i class="material-icons">search</i>
        </a>
        <vx-tooltip text="Editar reserva" v-if="isRole('admin|liderprogramacion') && params.data.codEstado==10">
            <router-link :to="{path:'/app/reserva/nueva',query:{idReserva:params.data.idReserva}}"
                         class="text-inherit hover:text-primary">
                <span class="material-icons">edit</span>
            </router-link>
        </vx-tooltip>
        <vx-tooltip text="Segementar reserva"
                    v-if="canPermiso('reservas.segmentar') && params.data.codEstado==10 && params.data.unidadesOrdenTotal>1 && params.data.nroOrden==params.data.nroOrdenPadre">
            <span @click="abrirPoput=true" class="text-inherit hover:text-primary cursor-pointer">
                <span class="material-icons">list_alt</span>
            </span>
        </vx-tooltip>
        <vx-tooltip text="Planear reserva"
                    v-if="isRole('admin|liderprogramacion') && params.data.codEstado==10 && params.data.valorOrdenTotal>0">
            <span @click="abrirPoputNuevoPlan=true" class="text-inherit hover:text-primary">
                <span class="material-icons">plus_one</span>
            </span>
        </vx-tooltip>
        <vx-tooltip text="Renovar O.T"
                    v-if="isRole('admin|liderprogramacion') && params.data.idEstadoReserva==10">
            <span class="material-icons"
                  @click="actualizarEstadoOtCanceladas(params.data.idReserva,params.data.idOT,params.data.nroOrden)">cached</span>
        </vx-tooltip>
        <vx-tooltip text="Eliminar reserva" v-if="validarEliminacion(params)">
            <i class='material-icons text-danger' @click="eliminarReserva(params.data.idReserva,params.data.nroOrden)">delete</i>
        </vx-tooltip>
        <vs-popup classContent="popup-example" :title="'Segmentar reserva '+params.data.nroOrden "
                  :active.sync="abrirPoput">
            <reserva-edit ref="componente_segmentar_reserva" :id-reserva="params.data.idReserva"
                          @prueba="prueba"></reserva-edit>
        </vs-popup>
        <vs-popup class="" title="Planear - Asignar reserva" :active.sync="abrirPoputNuevoPlan">
            <ValidationObserver v-slot="{ handleSubmit }">
                <form @submit.prevent="handleSubmit(guardarPlanManual)">
                    <div class="vx-row">
                        <div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 mb-2">
                            <strong>Año:</strong>
                            <vs-select v-model="anio" @input="changeAnio"
                                       class="form-control" style="width:100%">
                                <vs-select-item :key="index" :value="item.id" :text="item.text"
                                                v-for="(item,index) in listaAnio"/>
                            </vs-select>
                        </div>
                        <div class="vx-col sm:w-12/12 md:6/12 lg:w-6/12 mb-2">
                            <span>Mes</span>
                            <validation-provider name="MES" rules="required"
                                                 v-slot="{ errors }">
                                <vs-select autocomplete class="form-control" @change="consultarCostoPlaneado"
                                           v-model="mes" style="width:100%">
                                    <vs-select-item :key="''" :value="''" :text="'---seleccione---'"/>
                                    <vs-select-item :key="index" :value="item.id" :text="item.text"
                                                    v-for="(item,index) in listaMes"/>
                                </vs-select>
                                <span class="text-danger">{{ errors[0] }}</span>
                            </validation-provider>
                        </div>
                        <div class="vx-col sm:w-12/12 md:4/12 lg:w-4/12 mb-2">
                            <vs-input type="number" disabled class="w-full"
                                      :value="convertirdecimal(costoPrevio)"
                                      label-placeholder="Planeado previo"
                                      icon-after>
                                <template #icon>
                                    <i class='bx bx-show-alt'></i>
                                </template>
                            </vs-input>
                        </div>
                        <div class="vx-col sm:w-12/12 md:4/12 lg:w-4/12 mb-2">
                            <vs-input type="number" disabled class="w-full"
                                      :value="convertirdecimal(monto)"
                                      label-placeholder="Valor está reserva"
                                      icon-after>
                                <template #icon>
                                    <i class='bx bx-show-alt'></i>
                                </template>
                            </vs-input>
                        </div>
                        <div class="vx-col sm:w-12/12 md:4/12 lg:w-4/12 mb-2">
                            <vs-input label="Meta mes" disabled class="w-full" :value="convertirdecimal(metaMes)"></vs-input>
                        </div>
                        <div class="vx-col sm:w-12/12 md:w-12/12 lg:w-12/12 w-full mb-2">
                            <span>Asignado a</span>
                            <validation-provider name="ASIGNADO A" rules="required"
                                                 v-slot="{ errors }">
                                <Select2 v-model="idUserAsignadoa" class="w-full" :options="listaAsistente"/>
                                <span class="text-danger">{{ errors[0] }}</span>
                            </validation-provider>
                        </div>
                    </div>
                    <div class="vx-row">
                        <div class="vx-col sm:w-12/12 md:12/12 lg:w-12/12 mb-2">
                            <boton type="submit" texto="Guardar plan" :cargando="loading"
                                   texto_cargando="Guardando plan">
                                <i class="bx bx-add-to-queue"></i>
                            </boton>
                        </div>
                    </div>
                </form>
            </ValidationObserver>
        </vs-popup>
    </div>
</template>

<script>
    import reservaEdit from '../../reservas/reserva_edit'

    export default {
        name: "CeldaRenderizarSegmentar",
        components: {reservaEdit},
        data() {
            return {
                abrirPoput: false,
                abrirPoputNuevoPlan: false,
                anio: new Date().getFullYear(),
                mes: '',
                monto: '',
                costoPrevio: null,
                metaMes: null,
                idUserAsignadoa: null,
                datosReserva: [],
                listaMes: [],
                listaAnio: [],
                listaAsistente: []
            }
        },
        methods: {
            prueba() {
                console.log("llego");
            },
            listarMeses() {
                this.monto = this.params.data.valorOrdenTotal;
                this.datosReserva = this.params.data;
                var temp_mes = new Date().getMonth();
                for (var j = temp_mes; j < this.meses.length; j++) {
                    this.listaMes.push({id: j + 1, text: this.meses[j]});
                }
                var anioActual = new Date().getFullYear();
                for (var i = anioActual; i <= (anioActual + 1); i++)
                    this.listaAnio.push({id: i, text: i});
            },
            consultarCostoPlaneado() {
                if (this.mes != null && this.mes != '') {
                    this.consultarMetaMes();
                    this.listarAsistentes();
                    this.cargandoGeneral = true;
                    axios.post("/consultas/consultarCostoPlaneado", {mes: this.mes})
                        .then(res => {
                            this.cargandoGeneral = false;
                            this.costoPrevio = res.data;
                        })
                }
            },
            consultarMetaMes() {
                axios.post("/consultas/consultarMetaMes", {mes: this.mes, anio: this.anio})
                    .then(res => {
                        if (typeof res.data == 'string') {
                            this.mostrarNotificacion("", res.data, "warning");
                            this.metaMes = 0;
                        } else
                            this.metaMes = res.data;
                    })
            },
            guardarPlanManual() {
                if (this.metaMes > 0) {
                    if (this.idUserAsignadoa != '' && this.idUserAsignadoa != null) {
                        this.cargandoGeneral = true;
                        axios.post("/planificacion/guardarPlanManual", {
                            datosPlan: {
                                planAnio: this.anio,
                                planMes: this.mes,
                                monto: this.monto,
                                metaMes: this.metaMes,
                                idUserAsignadoa: this.idUserAsignadoa
                            },
                            datosDetalle: this.datosReserva
                        })
                            .then(res => {
                                this.cargandoGeneral = false;
                                this.costoPrevio = this.anio = this.mes = this.monto = this.metaMes = null;
                                this.abrirPoputNuevoPlan = false;
                                this.mostrarNotificacion("Registro exitoso", "Se ha registrado el plan con exito!", res.data == 'ok' ? "primary" : 'danger');
                                this.$parent.$parent.listarReservas();
                            })
                    } else {
                        this.mostrarNotificacion("", "Debe selecionar el asistente!", "warning");
                    }
                } else {
                    this.mostrarNotificacion("", "Lo sentimos, no hay una meta para el mes seleccionado, registre la meta para el mes", "warning")
                }
            },
            listarAsistentes() {
                axios.post("/consultas/consultarAsistentes")
                    .then(res => {
                        this.listaAsistente = res.data;
                    })
            },
            actualizarEstadoOtCanceladas(idReserva, idOT, nroOrden) {
                this.$swal.fire({
                    title: 'Renovar O.T?',
                    text: "Desea renovar la O.T " + nroOrden + " ?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, renovar!'
                }).then((result) => {
                    if (result.value) {
                        axios.post("/ordenes/actualizarOtCancelada", {"idOT": idOT, "idReserva": idReserva})
                            .then(res => {
                                this.mostrarNotificacion("Registro exitoso", "Se ha actualizado la reserva con " + nroOrden + " exito!", "primary");
                                this.$parent.$parent.listarReservas();
                            })
                    }
                })

            },
            validarEliminacion(params) {
                if (this.isRole('admin|liderprogramacion') && (params.data.idEstadoReserva == 1 || params.data.idEstadoReserva == 2 || params.data.idEstadoReserva == 10)) {
                    return true;
                } else {
                    return false;
                }
            },
            eliminarReserva(idReserva, nroOrden) {
                this.$swal.fire({
                    title: 'Eliminar reserva',
                    html: "Desea eliminar la reserva con número de orden <strong>" + nroOrden + "</strong> ?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar!'
                }).then((result) => {
                    if (result.value) {
                        axios.post("/ordenes/eliminarReserva", {"idReserva": idReserva})
                            .then(res => {
                                if (res.data == 'ok') {
                                    this.mostrarNotificacion("Eliminación exitosa", "Se ha eliminado la reserva con referencia de orden " + nroOrden, "success");
                                    this.$parent.$parent.listarReservas();
                                } else {
                                    this.mostrarNotificacion("Eliminación fallida", "Lo sentimos, no se puede eliminar la reserva " + nroOrden + "::" + res.data, "danger");
                                }
                            })
                    }
                })
            },
            abrirPoputView(idReserva) {
                this.$parent.$parent.actualizarPoput(true, idReserva);
            },
            changeAnio() {
                var anioActual = new Date().getFullYear();
                if (anioActual != this.anio) {
                    this.listaMeses = [];
                    for (var j = 0; j < this.meses.length; j++) {
                        this.listaMeses.push({id: j + 1, text: this.meses[j]});
                    }
                }
                this.consultarCostoPlaneado();
            }
        },
        watch: {
            abrirPoput: function (val) {
                if (val == true) {
                    this.$refs.componente_segmentar_reserva.obtenerreservaId();
                    /* this.$root.$emit("obtenerreservaId");
                     const eventBus = new Vue ();
                     eventBus.$emit('obtenerreservaId');*/
                }
            },
            abrirPoputNuevoPlan: function (val) {
                if (val == true) {
                    this.mes = new Date().getMonth() + 1;
                    this.listarMeses();
                }
            }
        }
    }
</script>

<style scoped>

</style>
