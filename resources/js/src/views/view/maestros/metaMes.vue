<template>
    <div class="container-fluid">
        <div class="row">
            <div class="vx-col w-full mb-base">
                <vx-card title="">
                    <div class="flex flex-wrap items-center mb-3">
                        <vs-button color="warning" type="filled" @click="popupActivo=true;">
                            Agregar meta
                        </vs-button>
                        <vs-popup class="holamundo" title="Crear meta" :active.sync="popupActivo">
                            <ValidationObserver v-slot="{ handleSubmit }">
                                <form @submit.prevent="handleSubmit(guardarMeta)">
                                    <div class="vx-row">
                                        <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12w-full mb-2">
                                            <strong>Año:</strong>
                                            <validation-provider name="AÑO" rules="required" v-slot="{ errors }">
                                                <vs-input class="w-full" :disabled="true"
                                                          v-model="metaMes.anio"></vs-input>
                                                <span class="text-danger">{{ errors[0] }}</span>
                                            </validation-provider>
                                        </div>
                                        <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12w-full mb-2">
                                            <strong>Mes:</strong>
                                            <validation-provider name="MES" rules="required" v-slot="{ errors }">
                                                <vs-select v-model="metaMes.mes"
                                                           class="w-full select-large">
                                                    <vs-select-item :key="index" :value="item.id" :text="item.text"
                                                                    v-for="(item,index) in listaMeses" class="w-full"/>
                                                </vs-select>
                                                <span class="text-danger">{{ errors[0] }}</span>
                                            </validation-provider>
                                        </div>
                                        <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full mb-2">
                                            <strong>Meta:</strong>
                                            <validation-provider name="META" rules="required" v-slot="{ errors }">
                                                <vs-input class="w-full" v-model="metaMes.meta"></vs-input>
                                                <span class="text-danger">{{ errors[0] }}</span>
                                            </validation-provider>
                                        </div>
                                    </div>
                                    <div class="vx-row">
                                        <div
                                            class="vx-col sm:w-12/12 md:w-12/12 lg:w-12/12 w-full text-center d-flex justify-content-center">
                                            <boton texto_cargando="Guardando meta..." type="submit"
                                                   texto="Guardar meta" :cargando="activeLoading"></boton>
                                        </div>
                                    </div>
                                </form>
                            </ValidationObserver>
                        </vs-popup>
                    </div>

                    <vs-table :data="datosMeta" pagination :max-items="10" search>
                        <template slot="thead">
                            <vs-th>Id</vs-th>
                            <vs-th>Mes</vs-th>
                            <vs-th>Año</vs-th>
                            <vs-th>Meta</vs-th>
                        </template>

                        <template slot-scope="{data}">
                            <vs-tr :key="indextr" v-for="(tr, indextr) in data">

                                <vs-td :data="data[indextr].idMeta">
                                    {{ data[indextr].idMeta }}
                                </vs-td>

                                <vs-td :data="data[indextr].mes">
                                    {{meses[data[indextr].mes-1] }}
                                </vs-td>

                                <vs-td :data="data[indextr].anio">
                                    {{ data[indextr].anio }}
                                </vs-td>

                                <vs-td :data="data[indextr].meta">
                                    {{convertirdecimal(data[indextr].meta) }}
                                </vs-td>
                            </vs-tr>
                        </template>
                    </vs-table>
                </vx-card>
            </div>
        </div>
    </div>
</template>

<script>
    import Tooltip from "../../components/vuesax/tooltip/Tooltip";

    export default {
        name: "metaMes",
        components: {Tooltip},

        data() {
            return {
                listaClientes: [],
                popupActivo: false,
                datosMeta: [],
                tipo: '',
                poputEliminarCliente: false,
                idCliente: null,
                listaMeses: [],
                metaMes: {
                    mes: '',
                    anio: new Date().getFullYear(),
                    meta: ''
                }
            }
        },

        created() {
            var temp_mes = new Date().getMonth();
            for (var j = temp_mes; j < this.meses.length; j++) {
                this.listaMeses.push({id: j + 1, text: this.meses[j]});
            }
            this.getMetaMes();
        },

        methods: {
            getMetaMes() {
                this.cargandoGeneral = true;
                axios.post("/maestros/consultarListaMetaMes")
                    .then(res => {
                        this.cargandoGeneral = false;
                        this.datosMeta = res.data;
                    })
            },
            guardarMeta() {
                this.cargandoGeneral = true;
                axios.post("/maestros/guardarMetaMes", this.metaMes)
                    .then(res => {
                        this.cargandoGeneral = false;
                        if (res.data == 'ok') {
                            this.metaMes = {
                                mes: '',
                                anio: new Date().getFullYear(),
                                meta: ''
                            }
                            this.mostrarNotificacion("Datos guardados con exito!", "", "success");
                            this.getMetaMes();
                            this.popupActivo = false;
                        } else {
                            this.mostrarNotificacion(res.data, "", "warning");
                        }
                    })
            }
        },

    }
</script>

<style scoped>

</style>
