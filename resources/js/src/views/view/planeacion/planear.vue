<template>
    <vx-card title="Asistente de planeación de reservas" code-toggler>

        <p>A continuación seleccione los criterios para planear - asignar las reservas para un periodo especifico:
            <strong>
            </strong></p>

        <div class="mt-5">
            <form-wizard color="rgba(var(--vs-primary), 1)" errorColor="rgba(var(--vs-danger), 1)" :title="null"
                         :subtitle="null" finishButtonText="Submit">
                <tab-content title="Paso 1" class="mb-5" icon="feather icon-home" :before-change="validateStep1">
                    <ValidationObserver ref="form1">
                        <form @submit.prevent="validateStep1">
                            <div class="vx-row">
                                <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full mb-2">
                                    <validation-provider name="ID PLAN" rules="required|numeric"
                                                         v-slot="{ errors }">
                                        <vs-input disabled label="ID plan" v-model="idPlan" class="w-full"
                                                  name="idPlan"/>
                                        <span class="text-danger">{{ errors[0] }}</span>
                                    </validation-provider>
                                </div>
                                <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full mb-2">
                                    <validation-provider name="AÑO" rules="required"
                                                         v-slot="{ errors }">
                                        <vs-select @change="consultarPlanPrevio" v-model="anio"
                                                   class="w-full select-large" label="Año">
                                            <vs-select-item :key="index" :value="item.id" :text="item.text"
                                                            v-for="(item,index) in listaAnio" class="w-full"/>
                                        </vs-select>
                                        <span class="text-danger">{{ errors[0] }}</span>
                                    </validation-provider>
                                </div>
                                <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full mb-2">
                                    <validation-provider name="MES" rules="required"
                                                         v-slot="{ errors }">
                                        <vs-select @change="consultarPlanPrevio" v-model="mes"
                                                   class="w-full select-large" label="Mes">
                                            <vs-select-item :key="index" :value="item.id" :text="item.text"
                                                            v-for="(item,index) in listaMeses" class="w-full"/>
                                        </vs-select>
                                        <span class="text-danger">{{ errors[0] }}</span>
                                    </validation-provider>
                                </div>
                            </div>
                            <div class="vx-row">
                                <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full mb-2">
                                    <validation-provider name="META MES" rules="required"
                                                         v-slot="{ errors }">
                                        <vs-input label="Meta mes" icon-pack="material-icons" class="w-full"
                                                  icon="attach_money"
                                                  placeholder="Meta mes" v-model="metaMes"/>
                                        <span class="text-danger">{{ errors[0] }}</span>
                                    </validation-provider>
                                </div>
                                <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full mb-2">
                                    <validation-provider name="GESTORES DISPONIBLES" rules="required"
                                                         v-slot="{ errors }">
                                        <vs-input label="Gestores disponibles" icon-pack="material-icons" class="w-full"
                                                  icon="person"
                                                  placeholder="Gestores disponibles" v-model="gestoresDisponibles"/>
                                        <span class="text-danger">{{ errors[0] }}</span>
                                    </validation-provider>
                                </div>
                                <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full mb-2">
                                    <validation-provider name="TOTAL HORAS MES" rules="required"
                                                         v-slot="{ errors }">
                                        <vs-input label="Total horas mes" placeholder="Total horas mes" class="w-full"
                                                  v-model="totalHorasMes"/>
                                        <span class="text-danger">{{ errors[0] }}</span>
                                    </validation-provider>
                                </div>
                            </div>
                            <div class="vx-row">
                                <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full mb-2">
                                    <validation-provider name="PLAN PREVIO" rules="required"
                                                         v-slot="{ errors }">
                                        <vs-input label="Planeado previo" icon-pack="material-icons" class="w-full"
                                                  icon="attach_money"
                                                  placeholder="Plan previo" v-model="planPrevio"/>
                                        <span class="text-danger">{{ errors[0] }}</span>
                                    </validation-provider>
                                </div>

                                <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full mb-2">
                                    <validation-provider name="NOVEDADES (Hrs)" rules="required"
                                                         v-slot="{ errors }">
                                        <vs-input label="Novedades (Horas)" class="w-full" v-model="novedadesHora"/>
                                        <span class="text-danger">{{ errors[0] }}</span>
                                    </validation-provider>
                                </div>
                                <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full mb-2">
                                    <validation-provider name="TOTAL HORAS MES" rules="required"
                                                         v-slot="{ errors }">
                                        <vs-input label="Disponibilidad del mes" placeholder="Disponibilidad del mes"
                                                  class="w-full"
                                                  v-model="disponibilidadMes"/>
                                        <span class="text-danger">{{ errors[0] }}</span>
                                    </validation-provider>
                                </div>
                                <div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 w-full mb-2">
                                    <vs-chip class="ag-grid-cell-chip" color="primary">
                                        <span>{{ porcentajeMeta }}</span> / Meta
                                    </vs-chip>
                                </div>
                                <div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 w-full mb-2 d-flex justify-content-between text-right right">

                                    <vs-row vs-type="flex" vs-justify="flex-end">
                                        <vs-chip class="ag-grid-cell-chip" color="primary">
                                            <span>{{ porcentajeDisponibilidad }}</span> / Disp.
                                        </vs-chip>
                                    </vs-row>
                                </div>
                            </div>
                        </form>
                    </ValidationObserver>
                </tab-content>

                <!-- tab 2 content -->
                <tab-content title="Paso 2" class="mb-5" icon="feather icon-briefcase" :before-change="validateStep2">
                    <ValidationObserver ref="form2">
                        <form @submit.prevent="validateStep2">
                            <vs-row>
                                <vs-col vs-sm="12" vs-md="6" vs-lg="6">
                                    <strong>Prioridad planeación</strong>
                                    <validation-provider name="PRIORIDAD PLANEACION" rules="required"
                                                         v-slot="{ errors }">
                                        <ul class="leftx">
                                            <li>
                                                <vs-radio v-model="prioridadPlaneacion" vs-name="radios1"
                                                          vs-value="Meta">
                                                    Meta (<strong>{{convertirdecimal(meta)}}</strong>)
                                                </vs-radio>
                                            </li>
                                            <li>
                                                <vs-radio v-model="prioridadPlaneacion" vs-name="radios1"
                                                          vs-value="Disponibilidad">
                                                    Disponibilidad
                                                    (<strong>{{convertirdecimal(disponibilidadMes)}}</strong>)
                                                </vs-radio>
                                            </li>
                                            <li>
                                                <vs-radio v-model="prioridadPlaneacion" vs-name="radios1"
                                                          vs-value="Otro">
                                                    Otro
                                                </vs-radio>
                                            </li>
                                        </ul>
                                        <span class="text-danger">{{ errors[0] }}</span>
                                    </validation-provider>
                                    <validation-provider name="NUEVA META" rules="required|numeric"
                                                         v-if="prioridadPlaneacion=='Otro'"
                                                         v-slot="{ errors }">
                                        <vs-input placeholder="Valor nueva meta" class="w-full" id="inputOtraMeta"
                                                  @keydown="otraMetaCalcular($event)" v-model="otraMeta"/>
                                        <span class="text-danger">{{ errors[0] }}</span>
                                    </validation-provider>
                                </vs-col>
                                <vs-col vs-sm="12" vs-md="6" vs-lg="6">
                                    <vs-row>
                                        <vs-col vs-sm="12" vs-md="12" vs-lg="12" class="text-center">
                                            <p><strong>Planificación tipo reserva</strong></p>
                                        </vs-col>
                                        <vs-col vs-sm="12" vs-md="6" vs-lg="6">
                                            <strong>ARL </strong>
                                            <validation-provider name="VALOR TOTAL ARL"
                                                                 :rules="'required|max_value:'+valorTotalArlOriginal"
                                                                 v-slot="{ errors }">
                                                <vs-input-number :min="0" :max="valorTotalArlOriginal"
                                                                 v-model="valorTotalArl"/>
                                                <span class="text-danger">{{ errors[0] }}</span>
                                            </validation-provider>
                                            % {{porcentajeArl}}<br> - Disponible ARL
                                            {{convertirdecimal(valorTotalArlOriginal)}}
                                        </vs-col>
                                        <vs-col vs-sm="12" vs-md="6" vs-lg="6">
                                            <strong>COMERCIAL </strong>
                                            <validation-provider name="VALOR TOTAL COMERCIAL"
                                                                 :rules="'required|max_value:'+valorTotalComercialOriginal"
                                                                 v-slot="{ errors }">
                                                <vs-input-number min="0" :max="valorTotalComercialOriginal"
                                                                 v-model="valorTotalComercial"/>
                                                <span class="text-danger">{{ errors[0] }}</span>
                                            </validation-provider>
                                            % {{porcentajeComercial}}<br> - Disponible COMERCIAL
                                            {{convertirdecimal(valorTotalComercialOriginal)}}
                                        </vs-col>
                                    </vs-row>
                                </vs-col>
                            </vs-row>
                        </form>
                    </ValidationObserver>
                </tab-content>

                <!-- tab 3 content -->
                <tab-content title="Paso 3" class="mb-5" icon="feather icon-image" :before-change="validateStep3">
                    <ValidationObserver ref="form3">
                        <form @submit.prevent="validateStep3">
                            <div class="vx-row">
                                <div class="vx-col sm:w-12/12 md:w-3/12 lg:w-3/12w-full mb-2">
                                    <vs-input disabled label="Total ordenes" placeholder="Total ordenes" class="w-full"
                                              v-model="totalOrdenes"/>
                                </div>
                                <div class="vx-col sm:w-12/12 md:w-3/12 lg:w-3/12w-full mb-2">
                                    <vs-input disabled label="Facturable arl" placeholder="Facturable ARL"
                                              class="w-full"
                                              v-model="facturableArl"/>
                                </div>
                                <div class="vx-col sm:w-12/12 md:w-3/12 lg:w-3/12w-full mb-2">
                                    <vs-input disabled label="Facturable comercial" placeholder="Facturable COMERCIAL"
                                              class="w-full"
                                              v-model="facturableComercial"/>
                                </div>
                                <div class="vx-col sm:w-12/12 md:w-3/12 lg:w-3/12w-full mb-2">
                                    <vs-input disabled label="Total horas" placeholder="Total horas" class="w-full"
                                              v-model="totalHoras"/>
                                </div>
                            </div>
                        </form>
                    </ValidationObserver>
                </tab-content>
            </form-wizard>
        </div>
    </vx-card>
</template>

<script>
    import {FormWizard, TabContent} from 'vue-form-wizard'
    import 'vue-form-wizard/dist/vue-form-wizard.min.css'

    export default {
        data() {
            return {
                idPlan: null,
                anio: 2020,
                mes: '',
                meta: 0,
                metaMes: '',
                gestoresDisponibles: null,
                totalHorasMes: null,
                disponibilidadMes: null,
                planPrevio: null,
                novedadesHora: null,
                horasMes: null,
                porcentajeMeta: null,
                porcentajeDisponibilidad: null,
                porcentajeArl: null,
                porcentajeComercial: null,
                prioridadPlaneacion: null,
                valorTotalArl: null,
                valorTotalComercial: null,
                valorTotalArlOriginal: null,
                valorTotalComercialOriginal: null,
                otraMeta: null,
                metaOriginal: null,
                totalOrdenes: null,
                facturableArl: null,
                facturableComercial: null,
                totalHoras: null,
                listaAnio: [],
                listaMeses: [],
                datosPlanFinal: []
            }
        },
        created() {
            const anio = new Date().getFullYear();
            for (var i = anio; i <= anio; i++) {
                this.listaAnio.push({id: i, text: i});
            }
            var temp_mes = new Date().getMonth();
            for (var j = temp_mes; j < this.meses.length; j++) {
                this.listaMeses.push({id: j + 1, text: this.meses[j]});
            }
            this.consultarPlanPrevio();
        },
        methods: {
            validateStep1() {
                return new Promise((resolve, reject) => {
                    this.$refs.form1.validate().then(result => {
                        if (result) {
                            this.consultarBasePlan();
                            resolve(true)
                        } else {
                            reject("Valore incorrectos");
                        }
                    })
                })
            },
            validateStep2() {
                return new Promise((resolve, reject) => {
                    this.$refs.form2.validate().then(result => {
                        if (result) {
                            this.consultarPlanTipoArl();
                            resolve(true)
                        } else {
                            reject("Valore incorrectos");
                        }
                    })
                })
            },
            validateStep3() {
                return new Promise((resolve, reject) => {
                    this.$refs.form3.validate().then(result => {
                        if (result) {
                            this.guardarPlan();
                            resolve(true)
                        } else {
                            reject("Valore incorrectos");
                        }
                    })
                })
            },
            consultarPlanPrevio() {
                if ((this.anio != '' && this.anio != null) && (this.mes != '' && this.mes != null)) {
                    this.cargandoGeneral = true;
                    axios.post("/planificacion/consultarPlanPrevio", {anio: this.anio, mes: this.mes})
                        .then(res => {
                            console.log(res.data);
                            const datos = res.data;
                            this.metaMes = datos[0].meta;
                            this.metaOriginal = this.metaMes;
                            this.gestoresDisponibles = datos[1].length;
                            this.planPrevio = datos[2].planPrevio;
                            this.novedadesHora = parseInt(datos[3].horas);
                            this.idPlan = datos[4];

                            this.totalHorasMes = this.gestoresDisponibles * 205;
                            this.disponibilidadMes = this.totalHorasMes - this.novedadesHora;
                            this.meta = this.metaMes - this.planPrevio;
                            this.cargandoGeneral = false;
                        })
                }
            },
            consultarBasePlan() {
                axios.post("/planificacion/consultarBasePlan")
                    .then(res => {
                        this.valorTotalArl = res.data[0];
                        this.valorTotalComercial = res.data[1];
                        this.valorTotalArlOriginal = this.valorTotalArl;
                        this.valorTotalComercialOriginal = this.valorTotalComercial;
                        this.actualizarValoresMeta(this.meta);
                        console.log(res.data);
                    })
            },
            consultarPlanTipoArl() {
                this.cargandoGeneral = true;
                axios.post("/planificacion/consultarPlanTipoArl", {
                    totalArl: this.valorTotalArl,
                    totalComercial: this.valorTotalComercial,
                    prioridad: this.prioridadPlaneacion,
                    disponibilidad: this.disponibilidadMes
                })
                    .then(res => {
                        this.totalOrdenes = this.convertirdecimal(res.data[0]);
                        this.facturableArl = this.convertirdecimal(res.data[1]);
                        this.facturableComercial = this.convertirdecimal(res.data[2]);
                        this.totalHoras = this.convertirdecimal(res.data[3]);
                        this.datosPlanFinal = res.data[4];
                        var suma = 0;
                        $.each(this.datosPlanFinal, function (index, value) {
                                suma+=value.valorOrdenTotal;
                        });
                        console.log(suma);
                        this.cargandoGeneral = false;
                    })
            },
            otraMetaCalcular(e) {
                var valor = this.solonumeros(e);
                return valor;
            },
            rangoInput(e, max) {
                var valor = this.solonumeros(e);
                return valor;
            },
            actualizarValoresMeta(meta) {
                const meta80 = meta * 0.80;
                const meta20 = meta * 0.20;
                this.meta = meta;
                if (this.valorTotalArlOriginal > meta80) {
                    this.valorTotalArl = meta80;
                    this.porcentajeArl = 80;
                } else if (this.valorTotalArlOriginal < meta) {
                    this.valorTotalArl = this.valorTotalArlOriginal;
                    this.porcentajeArl = (this.valorTotalArlOriginal / meta) * 100;
                }
                if (this.valorTotalComercialOriginal > meta20) {
                    this.valorTotalComercial = meta20;
                    this.porcentajeComercial = 20;
                } else if (this.valorTotalComercialOriginal < meta) {
                    this.valorTotalComercial = this.valorTotalComercialOriginal;
                    this.porcentajeComercial = (this.valorTotalComercialOriginal / meta) * 100;
                }
            },
            guardarPlan() {
                const datosPlan = [{
                    planMes: this.mes,
                    planAnio: this.anio,
                    criterio: (this.prioridadPlaneacion == "Disponibilidad" ? 'DISPONIBILIDAD' : 'DINERO'),
                    monto: this.meta,
                    horasDisponibles: this.disponibilidadMes,
                    metaMes: this.metaMes,
                    porARL: this.porcentajeArl,
                    porComercial: this.porcentajeComercial
                }];
                axios.post("/planificacion/guardarPlan", {'datosPlan': datosPlan, reservas: this.datosPlanFinal})
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
            }
        },
        watch: {
            otraMeta: function (val) {
                const meta80 = val * 0.80;
                const meta20 = val * 0.20;
                this.actualizarValoresMeta(val);
            },
            prioridadPlaneacion: function (val) {
                if (val == "Otro")
                    this.metaMes = this.otraMeta;
                else if (val == "Meta") {
                    this.metaMes = this.metaOriginal;
                    this.actualizarValoresMeta(this.meta);
                    /*this.valorTotalArl = this.valorTotalArlOriginal;
                    this.valorTotalComercial = this.valorTotalComercialOriginal;*/
                }
            }
        },
        components: {
            FormWizard,
            TabContent
        }
    }
</script>

<style scoped>

</style>