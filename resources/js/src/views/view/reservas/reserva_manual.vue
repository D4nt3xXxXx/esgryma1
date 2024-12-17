<template>
    <div class="container-fluid">
        <div class="row">
            <div class="vx-col w-full mb-base">
                <ValidationObserver v-slot="{ handleSubmit }">
                    <form @submit.prevent="handleSubmit(guardarReserva)">
                        <vx-card title="Nueva reserva">
                            <div class="vx-row">
                                <div class="vx-col sm:w-1/4 w-full mb-2">
                                    <span>Tipo</span>
                                    <validation-provider name="Tipo" rules="required" v-slot="{ errors }">
                                        <vs-select autocomplete class="selectExample w-full" v-model="reserva.tipo">
                                            <vs-select-item :key="''" :value="''" :text="'---seleccione---'"/>
                                            <vs-select-item :key="index" :value="item.id" :text="item.text"
                                                            v-for="(item,index) in listaTipo"/>
                                        </vs-select>
                                        <span class="text-danger">{{ errors[0] }}</span>
                                    </validation-provider>
                                </div>
                                <div class="vx-col sm:w-1/4 w-full mb-2">
                                    <span>Cliente</span>
                                    <validation-provider name="Cliente" rules="required" v-slot="{ errors }">
                                        <Select2 class="w-full" v-model="reserva.cliente" :options="listaCliente"
                                                 @change="obtenerasistenteId($event)"/>
                                        <span class="text-danger">{{ errors[0] }}</span>
                                    </validation-provider>
                                </div>
                            </div>
                            <div class="vx-row">
                                <div class="vx-col sm:w-1/2 w-full mb-2">
                                    <validation-provider name="Numero Orden" rules="required" v-slot="{ errors }">
                                        <vs-input id="nroOrden" class="w-full" label-placeholder="Número Orden"
                                                  v-model="reserva.orden"/>
                                        <span class="text-danger">{{ errors[0] }}</span>
                                    </validation-provider>
                                </div>
                                <div class="vx-col sm:w-1/4 w-full mb-2">
                                    <span>Vigencia - Fecha de Inicio</span>
                                    <validation-provider name="Fecha Inicio" rules="required" v-slot="{ errors }">
                                        <datetime type="date"
                                                  input-class="vs-inputx vs-input--input normal"
                                                  value-zone="America/Bogota"
                                                  zone="America/Bogota" v-model="reserva.fecha_ini"
                                                  format="yyyy-MM-dd"
                                                  auto></datetime>
                                        <span class="text-danger">{{ errors[0] }}</span>
                                    </validation-provider>
                                </div>
                                <div class="vx-col sm:w-1/4 w-full mb-2">
                                    <span>Vigencia - Fecha Final</span>
                                    <validation-provider name="Fecha Final" rules="required" v-slot="{ errors }">
                                        <datetime :min-datetime="reserva.fecha_ini" type="date"
                                                  input-class="vs-inputx vs-input--input normal"
                                                  value-zone="America/Bogota"
                                                  zone="America/Bogota" v-model="reserva.fecha_fin"
                                                  format="yyyy-MM-dd"
                                                  auto></datetime>
                                        <span class="text-danger">{{ errors[0] }}</span>
                                    </validation-provider>
                                </div>
                                <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full mb-2">
                                    <validation-provider name="Empresa" rules="required" v-slot="{ errors }">
                                        <vs-input class="w-full" label-placeholder="Empresa" v-model="reserva.empresa"/>
                                        <span class="text-danger">{{ errors[0] }}</span>
                                    </validation-provider>
                                </div>
                                <div class="vx-col sm:w-12/12 md:w-8/12 lg:w-8/12 w-full mb-2"
                                     v-if="idReserva==null || idReserva==undefined">
                                    <span>Tema</span>
                                    <validation-provider name="Tema" rules="required" v-slot="{ errors }">
                                        <Select2 class="w-full" v-model="reserva.temaId" :options="listaActividades"
                                                 @select="obtenerActividad($event)"/>
                                        <span class="text-danger">{{ errors[0] }}</span>
                                    </validation-provider>
                                </div>
                                <div v-else class="vx-col sm:w-12/12 md:w-8/12 lg:w-8/12 w-full mb-2">
                                    <validation-provider name="Tema" rules="required" v-slot="{ errors }">
                                        <vs-input class="w-full" label-placeholder="Tema" v-model="reserva.tema"/>
                                        <span class="text-danger">{{ errors[0] }}</span>
                                    </validation-provider>
                                </div>
                                <div class="vx-col sm:w-12/12 md:w-3/12 lg:w-3/12 w-full mb-2">
                                    <stron>Participantes</stron>
                                    <validation-provider name="PARTICIPANTES"
                                                         :rules="tipoLiquidacion==true ? 'required' : ''"
                                                         v-slot="{ errors }">
                                        <vs-select autocomplete @input="calcularTotal" class="form-control"
                                                   v-model="reserva.participantes">
                                            <vs-select-item :key="''" :value="''" :text="'---seleccione---'"/>
                                            <vs-select-item :key="index" :value="item" :text="item"
                                                            v-for="(item,index) in 100"/>
                                        </vs-select>
                                        <span class="text-danger">{{ errors[0] }}</span>
                                    </validation-provider>
                                </div>
                                <div class="vx-col sm:w-12/12 md:w-3/12 lg:w-3/12 w-full mb-2">
                                    <validation-provider name="HORAS"
                                                         :rules="!tipoLiquidacion ? 'required|numeric' : ''"
                                                         v-slot="{ errors }">
                                        <vs-input @change="calcularTotal" class="w-full" label-placeholder="Horas"
                                                  v-model="reserva.unidades"/>
                                        <span class="text-danger">{{ errors[0] }}</span>
                                    </validation-provider>
                                </div>
                                <div class="vx-col sm:w-12/12 md:w-3/12 lg:w-3/12 w-full mb-2">
                                    <div class="center">
                                        <ul>
                                            <li>
                                                <p>Liquidar por unidades</p>
                                                <vs-switch v-model="tipoLiquidacion" @input="calcularTotal">
                                                    <template #off>
                                                        NO
                                                    </template>
                                                    <template #on>
                                                        SI
                                                    </template>
                                                </vs-switch>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                                <div class="vx-col sm:w-12/12 md:w-3/12 lg:w-3/12 w-full mb-2">
                                    <validation-provider name="Valor unidad" rules="required|numeric"
                                                         v-slot="{ errors }">
                                        <vs-input @change="calcularTotal" class="w-full"
                                                  label-placeholder="Valor unitario"
                                                  v-model="reserva.valor_unit"/>
                                        <span class="text-danger">{{ errors[0] }}</span>
                                    </validation-provider>
                                </div>
                                <div class="vx-col sm:w-12/12 md:w-3/12 lg:w-3/12 w-full mb-2">
                                    <validation-provider name="Valor total" rules="required|numeric"
                                                         v-slot="{ errors }">
                                        <vs-input disabled class="w-full" label-placeholder="Valor total"
                                                  v-model="reserva.valor_total"/>
                                        <span class="text-danger">{{ errors[0] }}</span>
                                    </validation-provider>
                                </div>
                                <!--<div class="vx-col sm:w-1/4 w-full mb-2">
                                    <span>Asignado a</span>
                                    <Select2 v-model="reserva.asignadoa" :options="listaAsistente"/>
                                </div>-->
                                <div v-if="idReserva==null || idReserva==undefined"
                                     class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12  w-full mb-2">
                                    <span>Gestor</span>
                                    <Select2 v-model="reserva.gestor" :options="listaGestor"
                                             @select="obtenerGestor($event)"/>
                                </div>
                                <div v-else
                                     class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12  w-full mb-2">
                                    <vs-input class="w-full" label-placeholder="Gestor" v-model="reserva.gestorNombre"/>
                                </div>
                                <vs-col vs-sm="12" vs-md="4" vs-lg="4" vs-align="flex-center" vs-type="flex"
                                        vs-justify="space-between">
                                    <vs-checkbox v-model="reserva.escurso">Es curso ?</vs-checkbox>
                                </vs-col>
                                <div class="vx-col sm:w-12/12 md:w-8/12 lg:w-8/12  w-full mb-2">
                                    <vs-input class="w-full" label-placeholder="Observación"
                                              v-model="reserva.observacion"/>
                                </div>
                            </div>
                            <div class="vx-row">
                                <div class="vx-col w-full text-center">
                                    <boton texto_cargando="Guardando..." type="submit"
                                           texto="Guardar reserva" :cargando="loading"></boton>
                                    <vs-button :to="{name:'reservas'}" color="dark">Cancelar</vs-button>
                                </div>
                            </div>
                        </vx-card>
                    </form>
                </ValidationObserver>
            </div>
        </div>
    </div>
</template>

<script>
    import ButtonGroup from "../../components/vuesax/button-group/ButtonGroup";
    import ButtonGroupVertical from '../../components/vuesax/button-group/ButtonGroupVertical'
    import {DateTime} from 'luxon';

    export default {
        name: "reserva_manual",
        components: {ButtonGroup, ButtonGroupVertical},
        data() {
            return {
                reserva: {
                    idReserva: 0,
                    orden: '',
                    temaId: null,
                    tema: '',
                    empresa: '',
                    valor_unit: '',
                    valor_total: '',
                    observacion: '',
                    fecha_ini: null,
                    fecha_fin: null,
                    unidades: '',
                    und_medida: '',
                    tipo: '',
                    asignadoa: '',
                    cliente: '',
                    estado: 1,
                    gestor: '',
                    gestorNombre: '',
                    participantes: null,
                    escurso: false
                },
                fechaActual: null,
                idReserva: null,
                tipoLiquidacion: false,
                listaTipoUnidades: [],
                listaTipo: [],
                listaCliente: [],
                listaGestor: [],
                listaAsistente: [],
                listaActividades: [],
                options1: [
                    {text: 'fdfdfdf', value: 0},
                    {text: 'fdfdsfdsfd', value: 2},
                    {text: 'ffdsfdf', value: 3},
                ],
            }
        },
        created() {
            this.idReserva = this.$route.query.idReserva;

            this.fechaActual = DateTime.local();
            this.listaUndMedida();
            this.listaTipoReserva();
            this.listaClientes();
            this.obtenerGestores();
            this.listarActividades();
            //this.listarAsistentes();
            if (this.idReserva != null && this.idReserva != undefined) {
                this.consultarReserva();
            }
        },
        mounted() {
            if (this.idReserva != null && this.idReserva != undefined) {
                $("#nroOrden").prop("disabled", true);
            }
        },
        methods: {
            listaUndMedida() {
                axios.post("/reservas/listaUndMedida")
                    .then(res => {
                        this.listaTipoUnidades = res.data;
                    })
            },
            listaTipoReserva() {
                axios.post("/reservas/listaTipoReserva")
                    .then(res => {
                        this.listaTipo = res.data;
                    });
            },
            listaClientes() {
                axios.post("/reservas/listaClientes")
                    .then(res => {
                        this.listaCliente = res.data;
                    });
            },
            obtenerAsistente() {
                axios.post("/reservas/listaasistenteClienteId", {idCliente: this.reserva.cliente})
                    .then(res => {
                        this.reserva.asignadoa = res.data[0].id;
                    })
            },
            listarAsistentes() {
                axios.post("/consultas/consultarAsistentes")
                    .then(res => {
                        this.listaAsistente = res.data;
                    })
            },
            obtenerasistenteId(val) {
                this.reserva.cliente = val;
                this.obtenerAsistente();
            },
            calcularTotal() {
                if (this.tipoLiquidacion == true) {
                    if (this.reserva.participantes != null && this.reserva.participantes != '')
                        this.reserva.valor_total = this.reserva.participantes * this.reserva.valor_unit;
                }
                else {
                    if (this.reserva.unidades > 0 && this.reserva.valor_unit > 0) {
                        this.reserva.valor_total = this.reserva.unidades * this.reserva.valor_unit;
                    }
                }
            },
            obtenerGestores() {
                axios.post("/reservas/listaGestores")
                    .then(res => {
                        this.listaGestor = res.data;
                    })
            }
            ,
            listarActividades() {
                axios.post("/consultas/listarActividades")
                    .then(res => {
                        this.listaActividades = res.data;
                    })
            }
            ,
            obtenerGestor({id, text}) {
                this.reserva.gestorNombre = text;
            }
            ,
            obtenerActividad({id, text}) {
                this.reserva.tema = text;
            }
            ,
            guardarReserva() {
                this.loading = true;
                this.cargandoGeneral = true;
                axios.post('/reservas/Guardar', {
                    nroOrden: this.reserva.orden,
                    nroOrdenPadre: this.reserva.orden,
                    temaOrden: this.reserva.tema,
                    empresaOrden: this.reserva.empresa,
                    valorOrdenUnit: this.reserva.valor_unit,
                    valorTotalOrdenTotal: this.reserva.valor_total,
                    obsOrden: this.reserva.observacion,
                    fecIniOrden: this.reserva.fecha_ini,
                    fecFinOrden: this.reserva.fecha_fin,
                    unidadesOrden: this.reserva.unidades,
                    undMedidaOrden: !this.tipoLiquidacion ? 'HORAS' : 'UNIDADES',
                    idTipo: this.reserva.tipo,
                    idCliente: this.reserva.cliente,
                    gestorSolicitado: this.reserva.gestorNombre,
                    idEstadoReserva: 0,
                    participantes: this.reserva.participantes,
                    asignadoa: this.reserva.asignadoa,
                    opcion: (this.idReserva != null && this.idReserva != undefined ? 'actualizar' : 'nueva'),
                    idReserva: this.idReserva,
                    escurso: this.reserva.escurso
                })
                    .then(res => {
                        if (this.idReserva != null && this.idReserva != undefined) {
                            this.mostrarNotificacion("Datos actualizados", "La reserva se ha actualizado con exito!", 'success')
                            this.consultarReserva();
                        } else {
                            if (res.data == 'ok') {
                                this.reset();
                                this.$swal("Registro exitoso");
                            }
                            else {
                                this.$swal(res.data);
                            }
                        }
                        this.cargandoGeneral = false;
                        this.loading = false;
                    })
            }
            ,
            consultarReserva() {
                this.cargandoGeneral = true;
                axios.post("/reservas/listareservaId", {idReserva: this.idReserva || 0})
                    .then(res => {
                        var datos = res.data[0];
                        this.reserva.idReserva = this.idReserva;
                        this.reserva.orden = datos.nroOrden;
                        this.reserva.tema = datos.temaOrden;
                        this.reserva.empresa = datos.empresaOrden;
                        this.reserva.valor_unit = datos.valorOrdenUnit;
                        this.reserva.valor_total = datos.valorOrdenTotal;
                        this.reserva.observacion = datos.obsOrden;
                        this.reserva.fecha_ini = datos.fecIniOrden;
                        this.reserva.fecha_fin = datos.fecFinOrden;
                        this.reserva.unidades = datos.unidadesOrden;
                        this.reserva.und_medida = datos.undMedidaOrden;
                        this.reserva.tipo = datos.idTipo;
                        this.reserva.cliente = datos.idCliente;
                        this.reserva.gestorNombre = datos.gestorSolicitado;
                        this.reserva.idEstadoReserva = datos.idEstadoReserva;
                        this.reserva.participantes = datos.participantes;
                        this.reserva.asignadoa = datos.idUserAsignadoa;
                        this.tipoLiquidacion = (datos.undMedidaOrden == 'HORAS' ? false : true);
                        this.reserva.escurso = datos.escurso;
                        this.cargandoGeneral = false;

                    }, err => {
                        this.cargandoGeneral = false;
                    })
            }
            ,
            reset() {
                this.reserva = {
                    orden: '',
                    tema: '',
                    empresa: '',
                    valor_unit: '',
                    valor_total: '',
                    observacion: '',
                    fecha_ini: null,
                    fecha_fin: '',
                    unidades: null,
                    und_medida: '',
                    tipo: '',
                    asignadoa: '',
                    cliente: '',
                    estado: 1,
                    gestor: '',
                    gestorNombre: ''
                };
            }
        }
    }
</script>

<style scoped>

</style>
