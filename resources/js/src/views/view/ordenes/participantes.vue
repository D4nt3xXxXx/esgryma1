<template>
    <div>
        <vs-card>
            <ValidationObserver v-slot="{ handleSubmit }">
                <form @submit.prevent="handleSubmit(agregarParticiapante)">
                    <div class="vx-row">
                        <div class="vx-col sm:w-12/12 md:w-3/12 lg:w-3/12 w-full mb-2">
                            <validation-provider name="CEDULA" rules="required|numeric"
                                                 v-slot="{ errors }">
                                <vs-input label="Cedúla" class="w-full"
                                          v-model="participante.cedula"/>
                                <span class="text-danger">{{ errors[0] }}</span>
                            </validation-provider>
                        </div>
                        <div class="vx-col sm:w-12/12 md:w-3/12 lg:w-3/12 w-full mb-2">
                            <validation-provider name="NOMBRE" rules="required"
                                                 v-slot="{ errors }">
                                <vs-input label="Nombre" class="w-full"
                                          v-model="participante.nombre"/>
                                <span class="text-danger">{{ errors[0] }}</span>
                            </validation-provider>
                        </div>
                        <div class="vx-col sm:w-12/12 md:w-3/12 lg:w-3/12 w-full mb-2">
                            <validation-provider name="Empresa" rules="required"
                                                 v-slot="{ errors }">
                                <vs-input label="Empresa" class="w-full"
                                          v-model="participante.empresa"/>
                                <span class="text-danger">{{ errors[0] }}</span>
                            </validation-provider>
                        </div>
                        <div class="vx-col sm:w-12/12 md:w-3/12 lg:w-3/12w-full mb-2">
                            <span>Tipo</span>
                            <validation-provider name="TIPO" rules="required"
                                                 v-slot="{ errors }">
                                <vs-select class="form-control"
                                           v-model="participante.tipo" style="width:100%">
                                    <vs-select-item value="" text="---seleccione---"/>
                                    <vs-select-item value="ARL" text="ARL"/>
                                    <vs-select-item value="COMERCIAL" text="COMERCIAL"/>
                                </vs-select>
                                <span class="text-danger">{{ errors[0] }}</span>
                            </validation-provider>
                        </div>
                    </div>
                    <vs-row>
                        <vs-col vs-sm="12" vs-md="12" vs-lg="12" vs-align="flex-start"
                                vs-type="flex" vs-justify="center">
                            <boton type="submit" texto="Agregar" :cargando="loading"
                                   texto_cargando="Agregando participante">
                                <i class="material-icons">exposure_plus_1</i>
                            </boton>
                        </vs-col>
                    </vs-row>
                </form>
            </ValidationObserver>
        </vs-card>
        <vs-card>
            <div class="vx-row">
                <div class="vs-col sm:w-12/12 md:w-12/12 lg:w-12/12">
                    <vs-table :data="datos">

                        <template slot="thead">
                            <vs-th>ID</vs-th>
                            <vs-th>Cedúla</vs-th>
                            <vs-th>Nombre</vs-th>
                            <vs-th>Empresa</vs-th>
                            <vs-th>Tipo</vs-th>
                            <vs-th>Facturado</vs-th>
                        </template>

                        <template slot-scope="{data}">
                            <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                                <vs-td :data="tr.idParticipante">
                                    {{ tr.idParticipante }}
                                </vs-td>
                                <vs-td :data="tr.cedula">
                                    {{ tr.cedula }}
                                </vs-td>
                                <vs-td :data="tr.nombre">
                                    {{ tr.nombre }}
                                </vs-td>
                                <vs-td :data="tr.empresa">
                                    {{ tr.empresa }}
                                </vs-td>
                                <vs-td :data="tr.tipo">
                                    <vs-chip v-if="tr.tipo=='ARL'" color="primary">
                                        {{ tr.tipo }}
                                    </vs-chip>
                                    <vs-chip v-else color="success">
                                        {{ tr.tipo }}
                                    </vs-chip>
                                </vs-td>
                                <vs-td :data="tr.facturado">
                                    <vs-chip v-if="tr.facturado==1" color="primary">
                                        SI
                                    </vs-chip>
                                    <vs-chip v-else>
                                        NO
                                    </vs-chip>
                                </vs-td>
                            </vs-tr>
                        </template>
                    </vs-table>
                </div>

            </div>
        </vs-card>
    </div>

</template>

<script>
    export default {
        name: "participantes",
        props: {
            datos: {
                required: true,
                type: [Object, Array]
            },

        },
        data() {
            return {
                participante: {
                    idParticipante: null,
                    cadula: null,
                    nombre: null,
                    empresa: null,
                    tipo: null,
                    facturado: 0,
                    idOT: null,
                    estado: 'Nuevo'
                }
            }
        },
        methods: {
            /*guardarParticipante() {
                axios.post("/ordenes/guardarParticiapantes", this.participante)
                    .then(res => {
                        console.log(res);
                    })
            }*/
            agregarParticiapante() {
                this.datos.push(this.participante);
            }
        },
        watch: {

        }
    }
</script>

<style scoped>

</style>