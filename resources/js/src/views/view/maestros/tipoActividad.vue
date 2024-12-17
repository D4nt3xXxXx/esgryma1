<template>
    <vx-card title="Maestro tipo actividad">
        <vs-button color="success" @click="agregarTipoActividad">Agregar tipo actividad</vs-button>
        <vs-table :data="datos" pagination :max-items="20" search>
            <template slot="thead">
                <vs-th>ID</vs-th>
                <vs-th sort-key="tipoActividad">Tipo actividad</vs-th>
                <vs-th>Prefijo</vs-th>
                <vs-th class="text-center center d-flex justify-center justify-end">Acciones</vs-th>
            </template>

            <template slot-scope="{data}">
                <vs-tr :key="indextr" v-for="(tr, indextr) in data">

                    <vs-td :data="data[indextr].idTipo">
                        {{ data[indextr].idTipo }}
                    </vs-td>

                    <vs-td :data="data[indextr].tipoActividad">
                        {{ data[indextr].tipoActividad }}
                    </vs-td>
                    <vs-td :data="data[indextr].prefijo">
                        {{ data[indextr].prefijo }}
                    </vs-td>
                    <vs-td>
                        <vs-row vs-type="flex" vs-justify="flex-end">
                            <vs-col vs-sm="6" vs-md="6" vs-lg="6" vs-type="flex" vs-justify="end"
                                    vs-align="end" vs-w="6">
                                <vx-tooltip text="Editar">
                                    <span @click="editarTipoActividad(data[indextr])" class="cursor-pointer"><i
                                            class="bx bx-edit-alt"></i></span>
                                </vx-tooltip>
                            </vs-col>
                            <vs-col vs-sm="6" vs-md="6" vs-lg="6" vs-type="flex" vs-justify="end"
                                    vs-align="end" vs-w="6">
                                <vx-tooltip text="Eliminar tipo novedad">
                                    <span @click="preguntarEliminarTipoActividad(data[indextr].idTipo)"
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
                <form @submit.prevent="guardarTipoActividad">
                    <div class="vx-row">
                        <div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 w-full mb-2">
                            <validation-provider name="TIPO ACTIVIDAD" rules="required"
                                                 v-slot="{ errors }">
                                <vs-input label="Tipo actividad" v-model="tipoActividad.tipoActividad"
                                          class="w-full"/>
                                <span class="text-danger">{{ errors[0] }}</span>
                            </validation-provider>
                        </div>
                        <div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 w-full mb-2">
                            <validation-provider name="PREFIJO" rules="required|max:5"
                                                 v-slot="{ errors }">
                                <vs-input label="Prefijo" v-model="tipoActividad.prefijo"
                                          style="text-transform: uppercase"
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
        name: "tipoActividad",
        data() {
            return {
                datos: [],
                tipoActividad: {
                    idTipo: 0,
                    tipoActividad: null,
                    prefijo: null
                },
                poputActivo: false,
                idTipoActividad: null
            }
        },
        created() {
            this.consultarTipoActividad();
        },
        methods: {
            consultarTipoActividad() {
                this.cargandoGeneral = true;
                axios.post("/maestros/consultarTipoActividad")
                    .then(res => {
                        this.datos = res.data;
                        this.cargandoGeneral = false;
                    })
            },
            agregarTipoActividad() {
                this.tipoActividad = {
                    idTipo: 0,
                    tipoActividad: null,
                    prefijo: null
                };
                this.poputActivo = true;
            },
            guardarTipoActividad() {
                this.$refs.form.validate().then(success => {
                    if (!success) {
                        return;
                    }
                    this.activeLoading = true;
                    axios.post("/maestros/guardarTipoActividad", this.tipoActividad)
                        .then(res => {
                            this.consultarTipoActividad();
                            this.mostrarNotificacion("Guardar tipo actividad", "Los datos se han guardado con exito!", "success");
                            this.activeLoading = false;
                            this.poputActivo = false;
                        })
                });
            },
            preguntarEliminarTipoActividad(id) {
                this.idTipoActividad = id;
                this.$vs.dialog({
                    type: 'confirm',
                    color: 'danger',
                    title: `Eliminar tipo de actividad`,
                    text: 'Desea eliminar el tipo de actividad seleccionado ?',
                    accept: this.eliminarTipoActividad
                })
            },
            eliminarTipoActividad() {
                axios.post("/maestros/eliminarTipoActividad", {id: this.idTipoActividad})
                    .then(res => {
                        this.mostrarNotificacion("Eliminar tipo actividad", res.data, "success");
                        this.datos.splice(this.datos.findIndex(x => x.idTipo === this.idTipoActividad), 1);
                    })
            },
            editarTipoActividad(datos) {
                this.tipoActividad.idTipo = datos.idTipo;
                this.tipoActividad.tipoActividad = datos.tipoActividad;
                this.tipoActividad.prefijo = datos.prefijo;
                this.poputActivo = true;
            }
        }
    }
</script>

<style scoped>

</style>