<template>
    <ValidationObserver v-slot="{ handleSubmit }" ref="formNovedad"
                        tag="form">
        <form @submit.prevent="handleSubmit(guardarNovedad)">
            <vs-card title="Nueva novedad">
                <div class="vx-row">
                    <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 mb-2">
                        <span>Tipo novedad</span>
                        <validation-provider name="TIPO NOVEDAD" rules="required|numeric"
                                             v-slot="{ errors }">

                            <vs-select autocomplete class="selectExample w-full"
                                       v-if="isRole('admin|liderprogramacion')"
                                       v-model="novedad.idTipoNovedad">
                                <vs-select-item :key="''" :value="''"
                                                :text="'---seleccione---'"/>
                                <vs-select-item :key="index" :value="item.id" class="disabledx"
                                                :text="item.text"
                                                v-for="(item,index) in listaTipoNovedad"/>
                            </vs-select>
                            <vs-select autocomplete class="selectExample w-full"
                                       v-else-if="isRole('admin|asisadministrativo')"
                                       v-model="novedad.idTipoNovedad">
                                <vs-select-item :key="''" :value="''"
                                                :text="'---seleccione---'"/>
                                <vs-select-item :key="index" :value="item.id" class="disabledx"
                                                v-if="item.rolvalidar=='1,2'"
                                                :text="item.text"
                                                v-for="(item,index) in listaTipoNovedad"/>
                            </vs-select>
                            <span class="text-danger">{{ errors[0] }}</span>
                        </validation-provider>
                    </div>
                    <div class="vx-col sm:w-12/12 md:w-8/12 lg:w-8/12 mb-2">
                        <div class="vx-row flex align-bottom justify-space-between ">
                            <div class="vx-col sm:w-11/12 md:w-11/12 lg:w-11/12">
                                <span>Gestor</span>
                                <validation-provider name="GESTOR" rules="required|numeric"
                                                     v-slot="{ errors }">
                                    <vs-select
                                        placeholder="Seleccione el(los) gestor(es)"
                                        multiple
                                        autocomplete
                                        class="gestores"
                                        v-model="novedad.idGestor"
                                        width="100%"
                                        id="gestores"
                                        :disabled="validarFechas"
                                    >
                                        <vs-select-item :key="index" :value="item.id" :text="item.text"
                                                        v-for="(item,index) in listaGestores"/>
                                    </vs-select>
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
                </div>
                <div class="vx-row">
                    <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 mb-2">
                        <strong>Fecha Inicio</strong>
                        <validation-provider name="FECHA INICIO" rules="required"
                                             v-slot="{ errors }">
                            <datetime type="datetime" input-class="vs-inputx vs-input--input normal"
                                      value-zone="America/Bogota"
                                      zone="America/Bogota" v-model="novedad.fechaInicio"
                                      format="yyyy-MM-dd H:m"
                                      auto></datetime>
                            <span class="text-danger">{{ errors[0] }}</span>
                        </validation-provider>
                    </div>
                    <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12  mb-2">
                        <div class="vs-row">
                            <div class="vs-col sm:w-2/12 md:w-2/12 lg:w-2/12">
                                <strong>Horas</strong>
                                <vs-select
                                    autocomplete
                                    class="selectExample w-full" @input="sumarHorasFecha"
                                    v-model="horasAdicional"
                                >
                                    <vs-select-item :key="index" :value="item" :text="item" v-for="(item,index) in 40"/>
                                </vs-select>
                            </div>
                            <div class="vs-col sm:w-10/12 md:w-10/12 lg:w-10/12">
                                <strong>Fecha Fin</strong>
                                <validation-provider name="FECHA FIN" rules="required"
                                                     v-slot="{ errors }">
                                    <datetime type="datetime" input-class="vs-inputx vs-input--input normal"
                                              value-zone="America/Bogota"
                                              zone="America/Bogota" v-model="novedad.fechaFin"
                                              format="yyyy-MM-dd H:m"
                                              :min-datetime="novedad.fechaInicio"
                                              auto></datetime>
                                    <span class="text-danger">{{ errors[0] }}</span>
                                </validation-provider>
                            </div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 mb-2">
                        <span>Horas disponibles (-)</span>
                        <validation-provider name="HORAS DISPONIBLES" rules="required"
                                             v-slot="{ errors }">
                            <vs-select autocomplete class="selectExample w-full"
                                       v-model="novedad.horas">
                                <vs-select-item :key="''" :value="''"
                                                :text="'---seleccione---'"/>
                                <vs-select-item :key="index" :value="item"
                                                :text="item"
                                                v-for="(item,index) in listaHoras"/>
                            </vs-select>
                            <span class="text-danger">{{ errors[0] }}</span>
                        </validation-provider>
                    </div>
                    <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12  mb-2">
                        <vs-input label="Autoriza" class="w-full" v-model="novedad.autoriza"></vs-input>
                    </div>
                    <div class="vx-col sm:w-12/12 md:w-8/12 lg:w-8/12  mb-2">
                        <vs-input label="Observación" class="w-full" v-model="novedad.observacion"></vs-input>
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
</template>

<script>
    import moment from 'moment'
    import {DateTime} from 'luxon'

    export default {
        name: "nuevaNovedad",
        data() {
            return {
                fechaActual: DateTime.local(),
                novedad: {
                    idTipoNovedad: null,
                    idGestor: [],
                    horas: null,
                    fechaInicio: null,
                    fechaFin: null,
                    autoriza: null,
                    observacion: null,
                },
                horasAdicional: null,
                opcion: '',
                listaTipoNovedad: [],
                listaGestores: [],
                listaHoras: []
            }
        },
        created() {
            this.opcion = this.$route.query.opcion;

            this.listarGestores();
            this.listarNovedades();
        },
        methods: {
            guardarNovedad() {
                this.loading = true;
                axios.post("/procesos/novedad.guardar", this.novedad)
                    .then(res => {
                        this.loading = false;
                        this.mostrarNotificacion("Registro exitoso", "Se ha registrado la novedad con exito!", "success");
                        this.novedad.fechaFin = this.novedad.fechaInicio = this.novedad.observacion = this.novedad.autoriza = this.novedad.idTipoNovedad = null;
                        this.novedad.idGestor = [];
                        this.novedad.horas = null;
                        this.horasAdicional = null;
                    })
            },
            listarGestores() {
                axios.post("/reservas/listaGestores")
                    .then(res => {
                        this.listaGestores = res.data;
                    })
            },
            listarNovedades() {
                axios.post("/consultas/listarNovedades")
                    .then(res => {
                        this.listaTipoNovedad = res.data;
                    })
            },
            calcularHoras() {
                if ((this.novedad.fechaInicio != null && this.novedad.fechaInicio != '') && (this.novedad.fechaFin != null && this.novedad.fechaFin != '')) {
                    var fechaIni = moment(this.novedad.fechaInicio).locale("America/Bogota");//now
                    var fechaFin = moment(this.novedad.fechaFin).locale("America/Bogota");
                    var dif = fechaFin.diff(fechaIni, 'hours');
                    if (dif > 0)
                        this.listaHoras = dif;
                    else
                        this.listaHoras = [];
                }
            },
            sumarHorasFecha() {
                if (this.novedad.fechaInicio != null && this.novedad.fechaInicio1 != '') {

                    var fechaIni = moment(this.novedad.fechaInicio).locale("America/Bogota").add(this.horasAdicional, 'hours');//now
                    this.novedad.fechaFin = DateTime.fromISO(fechaIni.format(), {zone: 'America/Bogota'}).toISO();
                }
            },
            sumar_horas() {
                var fecha = document.getElementsByName("fecha")[0].value;
                var horas = document.getElementsByName("horas")[0].value;


                fecha = new Date(fecha);
                fecha.setHours(fecha.getHours() + horas);
                fecha = fecha.toISOString();
                var fecha_date = fecha.split('T');
                var fecha_time = fecha_date[1].split('.');
                var fecha_time = fecha_date[1].split(':');
                fecha_date = fecha_date[0];

                fecha = fecha_date + ' ' + fecha_time[0] + ':' + fecha_time[1];


                document.getElementsByName("log")[0].value = fecha;
            },
            validarFechas() {
                if ((this.novedad.fechaInicio != null && this.novedad.fechaInicio != '') && (this.novedad.fechaFin != null && this.novedad.fechaFin != '')) {
                    $("input[id=gestores]").prop("disabled", false);
                    this.gestoresDisponibles();
                    return false;
                } else {
                    $("input[id=gestores]").prop("disabled", true);
                    return true;
                }

            },
            gestoresDisponibles() {
                this.cargandoGeneral = true;
                axios.post("/reservas/listaGestoresDisponibles", this.novedad)
                    .then(res => {
                        this.listaGestores = res.data;
                        this.cargandoGeneral = false;
                    })
            }
        },
        watch: {
            'novedad.fechaInicio': {
                handler: function (after, before) {
                    this.calcularHoras();
                    this.validarFechas();
                },
                deep: true
            },
            'novedad.fechaFin': {
                handler: function (after, before) {
                    this.calcularHoras();
                    this.validarFechas();
                },
                deep: true
            }
        }
    }
</script>

<style scoped>

</style>
