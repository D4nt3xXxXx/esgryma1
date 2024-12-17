<template>
    <div>
        <vs-card>
            <vs-row>
                <div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 w-full mb-2">
                    <strong>Orden de servicio </strong>{{datos.nroOrden || ''}}
                </div>
                <div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 w-full mb-2">
                    <strong>Cliente </strong>{{cliente}}
                </div>
                <div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 mb-2">
                    <strong>O.T </strong>{{datos.idOT || ''}}
                </div>
                <div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 mb-2">
                    <strong>Empresa </strong> {{datos.empresaOrden || ''}}
                </div>
                <vs-col vs-sm="12" vs-md="12" vs-lg="12" vs-align="flex-end"
                        vs-type="flex" vs-justify="space-between">
                    <vs-button @click="abrirSwal" color="primary" type="filled">
                        Nueva Bitacora
                    </vs-button>
                </vs-col>
            </vs-row>
        </vs-card>
        <vs-card>
            <vs-table :data="datosBitacora">

                <template slot="thead">
                    <vs-th>ID</vs-th>
                    <vs-th>Motivo</vs-th>
                    <vs-th>Fecha</vs-th>
                    <vs-th>Registra</vs-th>
                </template>

                <template slot-scope="{data}">
                    <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in datosBitacora">
                        <vs-td :data="tr.idBitacora">
                            {{ tr.idBitacora }}
                        </vs-td>
                        <vs-td :data="tr.Observacion">
                            {{ tr.Observacion }}
                        </vs-td>
                        <vs-td :data="tr.fechaObs">
                            {{ tr.fechaObs }}
                        </vs-td>
                        <vs-td :data="tr.nombre">
                            {{ tr.nombre }}
                        </vs-td>
                    </vs-tr>
                </template>
            </vs-table>
        </vs-card>

        <vs-popup title="Inner popup" :active.sync="abrirPoputNuevaBitacora">
            <ValidationObserver ref="observadorBitacora" v-slot="{ invalid }" tag="form"
                                @submit.prevent="guardarBitacora()">
                <vs-row>
                    <!--<div class="vx-col sm:w-12/12 md:w-12/12 lg:w-12/12 w-full mb-2">
                        <strong>{{fecha || ''}}</strong>
                    </div>-->
                    <div class="vx-col sm:w-12/12 md:w-12/12 lg:w-12/12 w-full mb-2">
                        <validation-provider name="DESCRIPCION" rules="required"
                                             v-slot="{ errors }">
                            <vs-input label="Motivo" v-model="descripcion" class="w-full"></vs-input>
                            <span class="text-danger">{{ errors[0] }}</span>
                        </validation-provider>
                    </div>
                    <div class="vx-col sm:w-12/12 md:w-12/12 lg:w-12/12 w-full mb-2">
                        <boton type="submit" texto="Agregar" :cargando="loading"
                               texto_cargando="Agregando bitacora">
                        </boton>
                    </div>
                </vs-row>
            </ValidationObserver>
        </vs-popup>
    </div>
</template>

<script>
    export default {
        name: "bitacora",
        props: {
            datos: {
                required: true,
            },
            datosBitacora: {
                required: true,
                type: [Array, Object]
            },
            abrirPoput: {
                required: true,
                type: Boolean
            }
        },
        data() {
            return {
                idOT: null,
                orden: null,
                cliente: null,
                empresa: null,
                abrirPoputListar: false,
                abrirPoputNuevaBitacora: false,
                descripcion: null,
                fecha: ''
            }
        },
        methods: {
            abrirSwal() {
                this.$swal.fire({
                    title: 'Agregar bitacora',
                    input: 'text',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Guardar',
                    showLoaderOnConfirm: true,
                    preConfirm: (login) => {
                        if (login == null || login == '') {
                            this.$swal.showValidationMessage(
                                `Los sentimos: Debe agregar el motivo!`
                            )
                        } else {
                            const datos = {
                                nroOrden: this.datos.nroOrden,
                                idOT: this.datos.idOT,
                                idReserva: this.datos.idReserva,
                                Observacion: login
                            }
                            this.loading = true;
                            return axios.post("/ordenes/guardarBitacora", datos)
                                .then(res => {
                                    this.abrirPoputNuevaBitacora = false;
                                    this.observacion = null;
                                    this.loading = false;
                                    //LISTAMOS LA LISTA DE BITACORAS QUE ESTA EN EL COMPONENTE PADRE
                                    this.$parent.$parent.$parent.listarBitacoras();
                                    return res;
                                })
                        }
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.value) {
                        this.mostrarNotificacion("Registro exitoso", "Los datos de la bitacora se han registrado con exito!", "success");
                    }
                })
            },
            guardarBitacora() {
                const datos = {
                    nroOrden: this.datos.nroOrden,
                    idOT: this.datos.idOT,
                    idReserva: this.datos.idReserva,
                    Observacion: this.descripcion
                }
                this.loading = true;
                axios.post("/ordenes/guardarBitacora", datos)
                    .then(res => {
                        this.abrirPoputNuevaBitacora = false;
                        this.observacion = null;
                        this.loading = false;
                        this.listarBitacoras();
                        this.mostrarNotificacion("Registro exitoso", "Los datos de la bitacora se han registrado con exito!", "success");
                    })
            }
        },

        watch: {
            abrirPoput: function (val) {
                if (val) {
                    this.abrirPoputListar = true;
                    this.$emit("abrirBitacoraPoput");
                    this.listarBitacoras();
                    var temp_fecha = new Date();
                    this.fecha = temp_fecha.getDay() + "-" + (temp_fecha.getMonth() - 1) + "-" + temp_fecha.getFullYear() + " " + temp_fecha.getHours() + ":" + temp_fecha.getMinutes();
                }
                else {
                    this.abrirPoputListar = false;
                    this.$emit("abrirBitacoraPoput");
                }
            }
        }
    }
</script>

<style scoped>

</style>