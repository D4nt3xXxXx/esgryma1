<!-- =========================================================================================
  File Name: UserEdit.vue
  Description: User Edit Page
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->

<template>
    <div id="page-user-edit">
        <vx-card>
            <div slot="no-body" class="tabs-container px-6 pt-6">
                <vs-tabs class="tab-action-btn-fill-conatiner">
                    <vs-tab label="Segmentar" icon-pack="feather" icon="icon-grid">
                        <div class="tab-text">

                            <div class="vx-row" :key="item.id" v-for="item in unicareserva">
                                <div class="vx-col sm:w-3/3 w-full mb-4">
                                    <vx-card>
                                        <div class="vx-row">
                                            <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full mb-2">Orden Nro:
                                                {{item.nroOrden || ''}}
                                            </div>
                                            <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full mb-2">
                                                {{numHoras}} - horas
                                            </div>
                                            <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full mb-2">
                                                <validation-provider name="nroParticiones" rules="required"
                                                                     v-slot="{ errors }">
                                                    <Select2 class="w-100" v-model="nroParticiones"
                                                             @change="particionarReserva"
                                                             :options="listaHoras"/>
                                                    <span class="text-danger">{{ errors[0] }}</span>
                                                </validation-provider>
                                                <!-- <button @click="particionarReserva">Particionar</button> -->
                                            </div>
                                        </div>
                                    </vx-card>
                                </div>
                            </div>

                            <div class="vx-row">
                                <div class="vx-col sm:w-12/12 md:w-12/12 lg:w-12/12 w-full mb-2">
                                    <vs-list v-for="(item,index) in listaParticiones" :key="item.id">
                                        <vs-list-item :title="item.nroOrden" subtitle="">
                                            <input :key="'input_'+index" class="vs-inputx vs-input--input normal"
                                                   v-model="listaParticiones[index].unidadesOrden"
                                                   @keydown="remarcaHoras(item.nroOrden,$event,item.unidadesOrdenTotal)">
                                        </vs-list-item>
                                    </vs-list>
                                </div>
                            </div>

                            <div class="vx-row">
                                <div class="vx-col sm:w-3/3 w-full mb-2 mb-base">
                                    <boton texto_cargando="Guardando..." @click.native="guardarReserva"
                                           v-if="listaParticiones.length>0"
                                           texto="Guardar reservas" :cargando="loading"></boton>
                                </div>
                            </div>
                        </div>
                    </vs-tab>
                </vs-tabs>
            </div>
        </vx-card>
    </div>
</template>

<script>

    export default {
        name: "reserva_edit",
        props: {
            idReserva: {
                required: true,
            },
            opcion: {
                type: String,
                default: 'lider'
            }
        },
        data() {
            return {
                unicareserva: [],
                numHoras: 0,
                listaHoras: [],
                nroParticiones: null,
                listaParticiones: []
            }
        },

        /* mounted() {
             this.obtenerreservaId();
         },*/

        methods: {
            remarcaHoras(orden, e, totalHoras) {
                // for (var i = 0; i < this.nroParticiones; i++) {
                //     if (this.listaParticiones[i].nroOrden = orden) {
                //         this.listaParticiones[i].horas = tiempo;
                //     }
                // }
                var valorT = 0;
                var key = e.key;
                var res = this.solonumeros(e);
                if (res == true) {
                    /*if (key >= 0 && key <= 9) {
                        var valorTotal = parseFloat($(e.target).val() + "" + parseFloat(key));
                        valorT = valorTotal;
                        var totalhoras = parseFloat(this.validarHoras(orden)) + valorTotal;
                        if (totalhoras > totalHoras) {
                            this.$vs.notify({
                                title: 'Horas fuera de rango',
                                text: 'El número de horas ingresado no puede ser mayor al número de horas de la reserva',
                                color: 'warning'
                            })
                            e.preventDefault();
                            return;
                        };
                    } else {
                        if (key != "Backspace")
                            e.preventDefault();
                        return;
                    }
                    if (key == "Backspace") {
                        var datos = this.listaParticiones.find(x => x.nroOrden == orden);
                        datos.unidadesOrden = 0;
                        return;
                    }
                    var datos = this.listaParticiones.find(x => x.nroOrden == orden);
                    datos.unidadesOrden = valorT;
                    return true;*/
                } else {
                    e.preventDefault();
                }
            },
            validarHoras(nroOrden) {
                var total = 0;
                $.each(this.listaParticiones, function (index, value) {
                    if (value.nroOrden != nroOrden)
                        total += parseFloat(value.unidadesOrden);
                })
                return total;
            },

            particionarReserva() {
                this.listaParticiones = [];
                var a = this.numHoras, b = this.nroParticiones;
                var c = a / b, d = Math.round(c), e = d * b, f = a - e;
                var cont = 0;
                let temp_object = this.unicareserva[0];
                for (var i = 1; i <= this.nroParticiones; i++) {
                    let temp_object = Object.assign({}, this.unicareserva[0]);
                    this.listaParticiones.push(temp_object);
                    this.listaParticiones[cont]["idReserva"] = 0;
                    this.listaParticiones[cont]["nroOrden"] = temp_object.nroOrden + '-' + i;
                    //this.listaParticiones[cont]["horas"] = (i == b ? f + d : d);
                    this.listaParticiones[cont]["unidadesOrden"] = (i == b ? f + d : d);
                    this.listaParticiones[cont]["unidadesOrdenTotal"] = a;
                    cont++;
                }
            },

            obtenerreservaId() {
                axios.post("/reservas/listareservaId", {idReserva: this.idReserva})
                    .then(res => {
                        this.unicareserva = res.data;
                        this.numHoras = this.unicareserva[0].unidadesOrdenTotal;

                        for (var i = 2; i <= this.unicareserva[0].unidadesOrdenTotal; i++) {
                            this.listaHoras.push({id: i, text: i});
                        }
                        //this.nroParticiones = this.listaHoras[0].id;
                    }, err => {
                        console.log(err);
                    })
            },

            guardarReserva() {
                if (this.validarHoras(this.idReserva) == this.numHoras) {
                    this.loading = true;
                    this.unicareserva[0]["particiones"] = this.nroParticiones;
                    axios.post('/reservas/GuardarParticion', {
                        reserva: this.listaParticiones,
                        idReserva: this.idReserva,
                        totalHoras: this.numHoras,
                        opcion: this.opcion
                    })
                        .then(res => {
                            if (res.data == 'ok') {
                                this.$vs.notify({
                                    title: 'Registro exitoso',
                                    text: (this.nroParticiones.length > 1 ? 'Las reservas se encuentran en GESTION' : 'La reserva se encuentra en GESTION'),
                                    color: 'success'
                                });
                                location.reload();
                            }
                            else
                            // this.$swal(res.data);
                                console.log('todo fail');

                            this.loading = false;
                        })
                } else {
                    this.$vs.notify({
                        title: "Total de horas",
                        text: "El número de horas a segmentar debe ser igual al total de números de horas de la reserva.",
                        color: "warning"
                    });
                }
            }
        },
        watch: {
            idReserva: function (val) {
                this.obtenerreservaId();
            }
        }

    }
</script>


