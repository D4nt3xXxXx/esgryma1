<template>
    <vx-card title="Maestro tema">
        <vs-button color="success" @click="agregarTema">Agregar tema</vs-button>
        <vs-table :data="datos" pagination :max-items="20" search>
            <template slot="thead">
                <vs-th>ID</vs-th>
                <vs-th sort-key="temaActividad">Nombre</vs-th>
                <vs-th class="text-center center d-flex justify-center justify-end">Acciones</vs-th>
            </template>

            <template slot-scope="{data}">
                <vs-tr :key="indextr" v-for="(tr, indextr) in data">

                    <vs-td :data="data[indextr].idTema">
                        {{ data[indextr].idTema }}
                    </vs-td>

                    <vs-td :data="data[indextr].temaActividad">
                        {{ data[indextr].temaActividad }}
                    </vs-td>
                    <vs-td>
                        <vs-row vs-type="flex" vs-justify="flex-end">
                            <vs-col vs-sm="6" vs-md="6" vs-lg="6" vs-type="flex" vs-justify="end"
                                    vs-align="end" vs-w="6">
                                <vx-tooltip text="Editar">
                                    <span @click="editarTema(data[indextr])" class="cursor-pointer"><i
                                            class="bx bx-edit-alt"></i></span>
                                </vx-tooltip>
                            </vs-col>
                            <vs-col vs-sm="6" vs-md="6" vs-lg="6" vs-type="flex" vs-justify="end"
                                    vs-align="end" vs-w="6">
                                <vx-tooltip text="Eliminar">
                                    <span @click="preguntarEliminarTema(data[indextr].idTema)"
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
                <form @submit.prevent="guardarTema">
                    <div class="vx-row">
                        <div class="vx-col sm:w-12/12 md:w-12/12 lg:w-12/12 w-full mb-2">
                            <validation-provider name="NOMBRE" rules="required"
                                                 v-slot="{ errors }">
                                <vs-input label="Nombre" v-model="tema.temaActividad"
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
        name: "tema",
        data() {
            return {
                datos: [],
                tema: {
                    idTema: 0,
                    temaActividad: null
                },
                poputActivo: false,
                idTema: null
            }
        },
        created() {
            this.consultarTema();
        },
        methods: {
            consultarTema() {
                this.cargandoGeneral = true;
                axios.post("/maestros/consultarTema")
                    .then(res => {
                        this.datos = res.data;
                        this.cargandoGeneral = false;
                    })
            },
            agregarTema() {
                this.tema = {
                    idTema: 0,
                    temaActividad: null
                };
                this.poputActivo = true;
            },
            guardarTema() {
                this.activeLoading = true;
                axios.post("/maestros/guardarTema", this.tema)
                    .then(res => {
                        this.consultarTema();
                        this.mostrarNotificacion("Guardar tema", "Los datos se han guardado con exito!", "success");
                        this.activeLoading = false;
                        this.poputActivo = false;
                    })
            },
            preguntarEliminarTema(id) {
                this.idTema = id;
                this.$vs.dialog({
                    type: 'confirm',
                    color: 'danger',
                    title: `Eliminar tema`,
                    text: 'Desea eliminar el tema seleccionado ?',
                    accept: this.eliminarTema
                })
            },
            eliminarTema() {
                axios.post("/maestros/eliminarTema", {id: this.idTema})
                    .then(res => {
                        this.mostrarNotificacion("Eliminar tema", res.data, "success");
                        this.datos.splice(this.datos.findIndex(x => x.idTema === this.idTema), 1);
                    })
            },
            editarTema(datos) {
                this.tema.idTema = datos.idTema;
                this.tema.temaActividad = datos.temaActividad;
                this.poputActivo = true;
            }
        }
    }
</script>

<style scoped>

</style>