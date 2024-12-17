<template>
    <div class="container-fluid">
        <div class="row">
            <div class="vx-col w-full mb-base">
                <vx-card title="">
                    <ValidationObserver v-slot="{ handleSubmit }">
                        <form @submit.prevent="handleSubmit(guardarCliente)">
                            <div class="vx-row">
                                <div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 w-full mb-2">
                                    <validation-provider name="NIT" rules="required" v-slot="{ errors }">
                                        <vs-input class="w-full" label-placeholder="Nit"
                                                  v-model="cliente.nit"/>
                                        <span class="text-danger">{{ errors[0] }}</span>
                                    </validation-provider>
                                </div>
                                <div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 w-full mb-2">
                                    <validation-provider name="Nombre" rules="required" v-slot="{ errors }">
                                        <vs-input class="w-full" label-placeholder="Nombre"
                                                  v-model="cliente.nombreCliente"/>
                                        <span class="text-danger">{{ errors[0] }}</span>
                                    </validation-provider>
                                </div>
                                <div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 w-full mb-2">
                                    <span>Tipo</span>
                                    <validation-provider name="TIPO CLIENTE" rules="required" v-slot="{ errors }">
                                        <vs-select autocomplete class="form-control"
                                                   v-model="cliente.tipoCliente" style="width:100%">
                                            <vs-select-item :key="''" :value="''" :text="'---seleccione---'"/>
                                            <vs-select-item :key="index" :value="item.id" :text="item.text"
                                                            v-for="(item,index) in listaTipo"/>
                                        </vs-select>
                                        <span class="text-danger">{{ errors[0] }}</span>
                                    </validation-provider>
                                </div>
                                <div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 w-full mb-2">
                                    <span>Asistente asignado</span>
                                    <validation-provider name="ASISTENTE" rules="required" v-slot="{ errors }">
                                        <vs-select autocomplete class="form-control"
                                                   @input="obtenerNombreAsistente($event)"
                                                   v-model="idAsistente" style="width:100%">
                                            <vs-select-item :key="''" :value="''" :text="'---seleccione---'"/>
                                            <vs-select-item :key="index" :value="item.id" :text="item.text"
                                                            v-for="(item,index) in listaAsistente"/>
                                        </vs-select>
                                        <span class="text-danger">{{ errors[0] }}</span>
                                    </validation-provider>
                                </div>
                                <div class="vx-col sm:w-12/12 md:w-12/12 lg:w-12/12 w-full mb-2">
                                    <validation-provider name="NOMBRE CONTACTO" rules="required" v-slot="{ errors }">
                                        <vs-input class="w-full" label-placeholder="Nombre de contacto"
                                                  v-model="cliente.nombreContacto"/>
                                        <span class="text-danger">{{ errors[0] }}</span>
                                    </validation-provider>
                                </div>
                                <div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 w-full mb-2">
                                    <validation-provider name="TELEFONO CONTACTO" rules="required" v-slot="{ errors }">
                                        <vs-input class="w-full" label-placeholder="Teléfono de contacto"
                                                  v-model="cliente.telefonoContacto"/>
                                        <span class="text-danger">{{ errors[0] }}</span>
                                    </validation-provider>
                                </div>
                                <!--<div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 w-full mb-2">
                                    <validation-provider name="EMAIL CONTACTO" rules="required" v-slot="{ errors }">
                                        <vs-input class="w-full" label-placeholder="Correo de contacto"
                                                  v-model="cliente"/>
                                        <span class="text-danger">{{ errors[0] }}</span>
                                    </validation-provider>
                                </div>-->
                            </div>
                            <div class="vx-row">
                                <div class="vx-col w-full text-center">
                                    <boton texto_cargando="Guardando cliente..." type="submit"
                                           texto="Guardar cliente" :cargando="activeLoading"></boton>
                                </div>
                            </div>
                        </form>
                    </ValidationObserver>
                </vx-card>
            </div>
        </div>
    </div>
</template>

<script>
    import ButtonGroup from "../../components/vuesax/button-group/ButtonGroup";
    import ButtonGroupVertical from '../../components/vuesax/button-group/ButtonGroupVertical'

    export default {
        name: "clientes_agregar",
        components: {ButtonGroup, ButtonGroupVertical},
        props: {
            tipo: {
                type: String,
                default: 'nuevo'
            },
            datosCliente: {
                type: [Array, Object]
            }
        },
        data() {
            return {
                listaClientes: [],
                cliente: {
                    nit: '',
                    nombreCliente: '',
                    tipoCliente: null,
                    asesor: null,
                    nombreContacto: null,
                    telefonoContacto: null
                },
                idAsistente: null,
                listaAsistente: [],
                listaTipo: [
                    {
                        id: 'A',
                        text: 'A'
                    },
                    {
                        id: 'B',
                        text: 'B'
                    },
                    {
                        id: 'C',
                        text: 'C'
                    },
                ]
            }
        },
        created() {
            this.consultarAsistentes();
        },
        mounted() {

        },
        methods: {
            getClientes() {
                axios.post("/reservas/listaCliente")
                    .then(res => {
                        this.listaClientes = res.data;
                    })
            },
            consultarAsistentes() {
                axios.post("/maestros/consultarAsistentes")
                    .then(res => {
                        this.listaAsistente = res.data;
                    });
            },
            obtenerNombreAsistente(id) {
                var texto = this.listaAsistente.find(x => x.id == id).text;
                this.cliente.asesor = texto;
            },
            guardarCliente() {
                this.activeLoading = true;
                axios.post("/maestros/guardarCliente", {
                    datos: this.cliente,
                    idAsistente: this.idAsistente,
                    "tipo": this.tipo
                })
                    .then(res => {
                        this.activeLoading = false;
                        if (this.tipo == "nuevo")
                            this.mostrarNotificacion("Registro cliente", "El cliente se ha registrado con exito!", "success");
                        else
                            this.mostrarNotificacion("Registro actualizado", "El cliente se ha actualizado con exito!", "success");
                        this.reset();
                        this.$parent.$parent.$parent.getClientes();
                        this.$parent.$parent.$parent.popupActivo = false;
                    })
            },
            reset() {
                this.cliente = {
                    nit: '',
                    nombreCliente: '',
                    tipoCliente: null,
                    asesor: null,
                    nombreContacto: null,
                    telefonoContacto: null
                };
                this.idAsistente = null;
            }
        },
        watch: {
            'datosCliente': {
                handler: function handler(after, before) {
                    if (Object.keys(this.datosCliente).length > 0) {
                        this.cliente.idCliente = this.datosCliente.id;
                        this.cliente.nit = this.datosCliente.nit;
                        this.cliente.nombreCliente = this.datosCliente.nombre;
                        this.cliente.tipoCliente = this.datosCliente.tipo;
                        this.cliente.asesor = this.datosCliente.asesor;
                        this.cliente.nombreContacto = this.datosCliente.nombreContacto;
                        this.cliente.telefonoContacto = this.datosCliente.telefonoContacto;
                        this.cliente.idAsistentexcliente = this.datosCliente.idAsistentexcliente;
                        this.idAsistente = this.datosCliente.idAsistente;
                    } else {
                        this.reset();
                    }
                },
                deep: true
            }
        }
    }
</script>

<style scoped>

</style>