<template>
    <vx-card title="Maestro grupo">
        <vs-button color="success" @click="agregarGrupo">Agregar grupo</vs-button>
        <vs-table :data="datos" pagination :max-items="20" search>
            <template slot="thead">
                <vs-th>ID</vs-th>
                <vs-th sort-key="nomGrupo">Nombre</vs-th>
                <vs-th class="text-center center d-flex justify-center justify-end">Acciones</vs-th>
            </template>

            <template slot-scope="{data}">
                <vs-tr :key="indextr" v-for="(tr, indextr) in data">

                    <vs-td :data="data[indextr].idGrupo">
                        {{ data[indextr].idGrupo }}
                    </vs-td>

                    <vs-td :data="data[indextr].nomGrupo">
                        {{ data[indextr].nomGrupo }}
                    </vs-td>
                    <vs-td>
                        <vs-row vs-type="flex" vs-justify="flex-end">
                            <vs-col vs-sm="6" vs-md="6" vs-lg="6" vs-type="flex" vs-justify="end"
                                    vs-align="end" vs-w="6">
                                <vx-tooltip text="Editar">
                                    <span @click="editarGrupo(data[indextr])" class="cursor-pointer"><i
                                            class="bx bx-edit-alt"></i></span>
                                </vx-tooltip>
                            </vs-col>
                            <vs-col vs-sm="6" vs-md="6" vs-lg="6" vs-type="flex" vs-justify="end"
                                    vs-align="end" vs-w="6">
                                <vx-tooltip text="Eliminar">
                                    <span @click="preguntarEliminarGrupo(data[indextr].idGrupo)"
                                          class="cursor-pointer"><i class="bx bxs-tag-x"></i></span>
                                </vx-tooltip>
                            </vs-col>
                        </vs-row>
                    </vs-td>
                </vs-tr>
            </template>
        </vs-table>

        <vs-popup class="holamundo"
                  :active.sync="poputActivo">
            <ValidationObserver ref="form">
                <form @submit.prevent="guardarGrupo">
                    <div class="vx-row">
                        <div class="vx-col sm:w-12/12 md:w-12/12 lg:w-12/12 w-full mb-2">
                            <validation-provider name="NOMBRE" rules="required"
                                                 v-slot="{ errors }">
                                <vs-input label="Nombre" v-model="grupo.nomGrupo"
                                          class="w-full"/>
                                <span class="text-danger">{{ errors[0] }}</span>
                            </validation-provider>
                        </div>
                    </div>
                    <div class="vx-row">
                        <div class="vx-col w-full text-center">
                            <boton texto_cargando="Guardando datos..." type="submit"
                                   texto="Guardar" :cargando="activeLoading"></boton>
                        </div>
                    </div>
                </form>
            </ValidationObserver>
        </vs-popup>
    </vx-card>
</template>

<script>
    export default {
        name: "grupo",
        data() {
            return {
                datos: [],
                grupo: {
                    idGrupo: 0,
                    nomGrupo: null
                },
                poputActivo: false,
                idGrupo: null
            }
        },
        created() {
            this.consultarGrupo();
        },
        methods: {
            consultarGrupo() {
                this.cargandoGeneral = true;
                axios.post("/maestros/consultarGrupo")
                    .then(res => {
                        this.datos = res.data;
                        this.cargandoGeneral = false;
                    })
            },
            agregarGrupo() {
                this.grupo = {
                    idGrupo: 0,
                    nomGrupo: null
                };
                this.poputActivo = true;
            },
            guardarGrupo() {
                this.activeLoading = true;
                axios.post("/maestros/guardarGrupo", this.grupo)
                    .then(res => {
                        this.consultarGrupo();
                        this.mostrarNotificacion("Guardar grupo", "Los datos se han guardado con exito!", "success");
                        this.activeLoading = false;
                        this.poputActivo = false;
                    })
            },
            preguntarEliminarGrupo(id) {
                this.idGrupo = id;
                this.$vs.dialog({
                    type: 'confirm',
                    color: 'danger',
                    title: `Eliminar grupo`,
                    text: 'Desea eliminar el grupo seleccionado ?',
                    accept: this.eliminarGrupo
                })
            },
            eliminarGrupo() {
                axios.post("/maestros/eliminarGrupo", {id: this.idGrupo})
                    .then(res => {
                        this.mostrarNotificacion("Eliminar grupo", res.data, "success");
                        this.datos.splice(this.datos.findIndex(x => x.idGrupo === this.idGrupo), 1);
                    })
            },
            editarGrupo(datos) {
                this.grupo.idGrupo = datos.idGrupo;
                this.grupo.nomGrupo = datos.nomGrupo;
                this.poputActivo = true;
            }
        }
    }
</script>

<style scoped>

</style>