<template>
    <div v-if="Object.keys(novedad).length>0">
        <ValidationObserver v-slot="{ handleSubmit }" ref="formNovedad"
                            tag="form">
            <form @submit.prevent="handleSubmit(editarNovedad)">
                <vs-card>
                    <div class="vx-row">
                        <div class="vx-col sm:w-12/12 md:w-12/12 lg:w-12/12 w-full mb-2">
                            <div class="vx-row flex align-bottom justify-space-between ">
                                <div class="vx-col sm:w-11/12 md:w-11/12 lg:w-11/12">
                                    <span>Gestor</span>
                                    <validation-provider name="GESTOR" rules="required|numeric"
                                                         v-slot="{ errors }">
                                        <Select2 v-model="novedad.idGestor" :options="listaGestores"
                                                 @select="obtenerGestor($event)"/>
                                        <span class="text-danger">{{ errors[0] }}</span>
                                    </validation-provider>
                                </div>
                                <vs-col vs-sm="1" vs-md="1" vs-lg="1" vs-align="flex-end"
                                        vs-type="flex" vs-justify="space-between">
                                    <vs-button color="dark" type="line"
                                               icon="event_note"></vs-button>
                                </vs-col>
                            </div>
                        </div>
                        <div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 w-full mb-2">
                            <strong>Fecha Inicio</strong>
                            <validation-provider name="FECHA INICIO" rules="required"
                                                 v-slot="{ errors }">
                                <datetime type="datetime" input-class="vs-inputx vs-input--input normal"
                                          value-zone="America/Bogota"
                                          zone="America/Bogota" v-model="novedad.fechaInicio"
                                          format="yyyy-MM-dd HH:mm"
                                          auto></datetime>
                                <span class="text-danger">{{ errors[0] }}</span>
                            </validation-provider>
                        </div>
                        <div class="vs-col sm:w-12/12 md:w-6/12 lg:w-6/12 w-full mb-2">
                            <strong>Fecha Fin</strong>
                            <validation-provider name="FECHA FIN" rules="required"
                                                 v-slot="{ errors }">
                                <datetime type="datetime" input-class="vs-inputx vs-input--input normal"
                                          value-zone="America/Bogota"
                                          zone="America/Bogota" v-model="novedad.fechaFin"
                                          format="yyyy-MM-dd HH:mm"
                                          :min-datetime="novedad.fechaInicio"
                                          auto></datetime>
                                <span class="text-danger">{{ errors[0] }}</span>
                            </validation-provider>
                        </div>
                        <div class="vs-col sm:w-12/12 md:w-12/12 lg:w-12/12 w-full">
                            <strong>Observación</strong>
                            <vs-textarea class="w-full" v-model="novedad.observacion"></vs-textarea>
                        </div>
                    </div>
                    <div class="vx-row flex justify-content-center justify-center">
                        <div class="vx-col sm:w-12/12 md:w-12/12 lg:w-12/12  mb-2 center text-center">
                            <boton texto_cargando="Guardando novedad..." type="submit"
                                   texto="Guardar novedad" :cargando="loading"></boton>
                        </div>
                    </div>
                </vs-card>
            </form>
        </ValidationObserver>
    </div>
</template>

<script>
    import vSelect from 'vue-select'
    import moment from "moment";
    import {DateTime} from 'luxon';

    export default {
        name: "editarNovedad",
        components: {vSelect},
        props: {
            novedad: {
                type: [Array, Object],
                required: true
            }
        },
        data() {
            return {
                listaGestores: []
            }
        },
        created() {
            this.obtenerGestores();
        },
        methods: {
            editarNovedad() {
                this.loading = true;
                this.calcularHoras();
                axios.post("/procesos/novedad.editar", this.novedad)
                    .then(res => {
                        this.loading = false;
                        this.mostrarNotificacion("Modificación exitosa", "Se ha actualizado la novedad con exito!", "success");
                        this.novedad = {};
                        this.$parent.$parent.infoActualizada();
                    })
            },
            obtenerGestores() {
                axios.post("/reservas/listaGestores")
                    .then(res => {
                        this.listaGestores = res.data;
                    })
            },
            calcularHoras() {
                if ((this.novedad.fechaInicio != null && this.novedad.fechaInicio != '') && (this.novedad.fechaFin != null && this.novedad.fechaFin != '')) {
                    var fechaIni = moment(this.novedad.fechaInicio).locale("America/Bogota");//now
                    var fechaFin = moment(this.novedad.fechaFin).locale("America/Bogota");
                    var dif = fechaFin.diff(fechaIni, 'hours');
                    this.novedad.horas = dif;
                }
            },
        },
        watch: {
            novedad(val) {
                if ((this.novedad.fechaInicio != null && this.novedad.fechaInicio != '') && (this.novedad.fechaFin != null && this.novedad.fechaFin != '')) {
                    this.novedad.fechaInicio = DateTime.fromSQL(this.novedad.fechaInicio).toISO();
                    this.novedad.fechaFin = DateTime.fromSQL(this.novedad.fechaFin).toISO();
                }
            }
        }
    }
</script>

<style scoped>

</style>
