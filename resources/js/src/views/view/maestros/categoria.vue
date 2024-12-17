<template>
    <vx-card title="Maestro categoria">
        <vs-button color="success" @click="agregarCategoria">Agregar categoria</vs-button>
        <vs-table :data="datos" pagination :max-items="20" search>
            <template slot="thead">
                <vs-th>ID</vs-th>
                <vs-th sort-key="categoriaActividad">Categoria</vs-th>
                <vs-th class="text-center center d-flex justify-center justify-end">Acciones</vs-th>
            </template>

            <template slot-scope="{data}">
                <vs-tr :key="indextr" v-for="(tr, indextr) in data">

                    <vs-td :data="data[indextr].idCategoria">
                        {{ data[indextr].idCategoria }}
                    </vs-td>

                    <vs-td :data="data[indextr].categoriaActividad">
                        {{ data[indextr].categoriaActividad }}
                    </vs-td>
                    <vs-td>
                        <vs-row vs-type="flex" vs-justify="flex-end">
                            <vs-col vs-sm="6" vs-md="6" vs-lg="6" vs-type="flex" vs-justify="end"
                                    vs-align="end" vs-w="6">
                                <vx-tooltip text="Editar">
                                    <span @click="editarCategoria(data[indextr])" class="cursor-pointer"><i
                                            class="bx bx-edit-alt"></i></span>
                                </vx-tooltip>
                            </vs-col>
                            <vs-col vs-sm="6" vs-md="6" vs-lg="6" vs-type="flex" vs-justify="end"
                                    vs-align="end" vs-w="6">
                                <vx-tooltip text="Eliminar tipo novedad">
                                    <span @click="preguntarEliminarCategoria(data[indextr].idCategoria)"
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
                <form @submit.prevent="guardarCategoria">
                    <div class="vx-row">
                        <div class="vx-col sm:w-12/12 md:w-12/12 lg:w-12/12 w-full mb-2">
                            <validation-provider name="CATEGORIA" rules="required"
                                                 v-slot="{ errors }">
                                <vs-input label="Categoria" v-model="categoria.categoriaActividad"
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
        name: "categoria",
        data() {
            return {
                datos: [],
                categoria: {
                    idCategoria: 0,
                    categoriaActividad: null
                },
                poputActivo: false,
                idCategoria: null
            }
        },
        created() {
            this.consultarCategoria();
        },
        methods: {
            consultarCategoria() {
                this.cargandoGeneral = true;
                axios.post("/maestros/consultarCategoria")
                    .then(res => {
                        this.datos = res.data;
                        this.cargandoGeneral = false;
                    })
            },
            agregarCategoria() {
                this.categoria = {
                    idCategoria: 0,
                    categoriaActividad: null
                };
                this.poputActivo = true;
            },
            guardarCategoria() {
                this.activeLoading = true;
                axios.post("/maestros/guardarCategoria", this.categoria)
                    .then(res => {
                        this.consultarCategoria();
                        this.mostrarNotificacion("Guardar categoria", "Los datos se han guardado con exito!", "success");
                        this.activeLoading = false;
                        this.poputActivo = false;
                    })
            },
            preguntarEliminarCategoria(id) {
                this.idCategoria = id;
                this.$vs.dialog({
                    type: 'confirm',
                    color: 'danger',
                    title: `Eliminar categoria`,
                    text: 'Desea eliminar la categoria seleccionada ?',
                    accept: this.eliminarCategoria
                })
            },
            eliminarCategoria() {
                axios.post("/maestros/eliminarCategoria", {id: this.idCategoria})
                    .then(res => {
                        this.mostrarNotificacion("Eliminar categoria", res.data, "success");
                        this.datos.splice(this.datos.findIndex(x => x.idCategoria === this.idCategoria), 1);
                    })
            },
            editarCategoria(datos) {
                this.categoria.idCategoria = datos.idCategoria;
                this.categoria.categoriaActividad = datos.categoriaActividad;
                this.poputActivo = true;
            }
        }
    }
</script>

<style scoped>

</style>