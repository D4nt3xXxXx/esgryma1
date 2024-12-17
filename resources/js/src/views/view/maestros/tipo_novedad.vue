<script src="../../../layouts/components/vertical-nav-menu/navMenuItems.js"></script>
<template>
    <vx-card title="Maestro tipo de novedad">
        <vs-button color="success" @click="agregarTipoNovedad">Agregar tipo novedad</vs-button>
        <vs-table :data="datos" pagination :max-items="20" search>
            <template slot="thead">
                <vs-th>ID</vs-th>
                <vs-th sort-key="nombre">Nombre</vs-th>
                <vs-th>Color</vs-th>
                <vs-th class="text-center center d-flex justify-center justify-end">Acciones</vs-th>
            </template>

            <template slot-scope="{data}">
                <vs-tr :key="indextr" v-for="(tr, indextr) in data">

                    <vs-td :data="data[indextr].id">
                        {{ data[indextr].id }}
                    </vs-td>

                    <vs-td :data="data[indextr].nombre">
                        {{ data[indextr].nombre }}
                    </vs-td>
                    <vs-td :data="data[indextr].color">
                        <vs-chip :color="data[indextr].color">
                            {{ data[indextr].color }}
                        </vs-chip>
                    </vs-td>
                    <vs-td>
                        <vs-row vs-type="flex" vs-justify="flex-end">
                            <vs-col vs-sm="6" vs-md="6" vs-lg="6" vs-type="flex" vs-justify="end"
                                    vs-align="end" vs-w="6">
                                <vx-tooltip text="Editar">
                                    <span @click="editarTipoNovedad(data[indextr])" class="cursor-pointer"><i
                                            class="bx bx-edit-alt"></i></span>
                                </vx-tooltip>
                            </vs-col>
                            <vs-col vs-sm="6" vs-md="6" vs-lg="6" vs-type="flex" vs-justify="end"
                                    vs-align="end" vs-w="6">
                                <vx-tooltip text="Eliminar tipo novedad">
                                    <span @click="preguntarEliminarTipoNovedad(data[indextr].id)"
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
                <form @submit.prevent="guardarTipoNovedad">
                    <div class="vx-row">
                        <div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 w-full mb-2">
                            <validation-provider name="NOMBRE" rules="required"
                                                 v-slot="{ errors }">
                                <vs-input label="Nombre" v-model="tipoNovedad.tipoNovedad"
                                          class="w-full"/>
                                <span class="text-danger">{{ errors[0] }}</span>
                            </validation-provider>
                        </div>
                        <div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 w-full mb-2">
                            <validation-provider name="COLOR" rules="required"
                                                 v-slot="{ errors }">
                                <vs-select autocomplete class="w-full" label="Color"
                                           v-model="tipoNovedad.colorNovedad" style="width:100%" @input="validarColor">
                                    <vs-select-item :key="''" :value="''" :text="'---seleccione---'"/>
                                    <vs-select-item :style="'background-color:'+item.text " :key="index"
                                                    :value="item.id" :text="item.text"
                                                    v-for="(item,index) in colores">
                                    </vs-select-item>
                                </vs-select>
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
        name: "tipo_novedad",
        data() {
            return {
                datos: [],
                colores: [],
                tipoNovedad: {
                    idTipoNovedad: 0,
                    tipoNovedad: null,
                    colorNovedad: null,
                    validacbox: 1
                },
                poputActivo: false,
                idTipoNovedad: null
            }
        },
        created() {
            this.consultarTipoNovedad();
            this.consultarColores();
        },
        methods: {
            consultarTipoNovedad() {
                axios.post("/maestros/consultarTipoNovedad")
                    .then(res => {
                        this.datos = res.data;
                    })
            },
            eliminarTipoNovedad() {

            },
            consultarColores() {
                axios.post("/consultas/consultarColores")
                    .then(res => {
                        this.colores = res.data;
                    })
            },
            agregarTipoNovedad() {
                this.tipoNovedad = {
                    idTipoNovedad: 0,
                    tipoNovedad: null,
                    colorNovedad: null,
                    validacbox: 1
                };
                this.poputActivo = true;
            },
            validarColor() {
                var existe = this.datos.findIndex(x => x.color == this.tipoNovedad.colorNovedad);
                if (existe >= 0) {
                    this.tipoNovedad.colorNovedad = null;
                    this.mostrarNotificacion("Color seleccionado", "Lo sentimos este color ya esta asociado a un tipo de novedad", "warning");
                }
            },
            guardarTipoNovedad() {
                this.activeLoading = true;
                axios.post("/maestros/guardarTipoNovedad", this.tipoNovedad)
                    .then(res => {
                        this.consultarTipoNovedad();
                        this.mostrarNotificacion("Guardar tipo novedad", "Los datos se han guardado con exito!", "success");
                        this.activeLoading = false;
                        this.poputActivo = false;
                    })
            },
            preguntarEliminarTipoNovedad(id) {
                this.idTipoNovedad = id;
                this.$vs.dialog({
                    type: 'confirm',
                    color: 'danger',
                    title: `Eliminar tipo de novedad`,
                    text: 'Desea eliminar el tipo de novedad seleccionado ?',
                    accept: this.eliminarTipoNovedad
                })
            },
            eliminarTipoNovedad() {
                axios.post("/maestros/eliminarTipoNovedad", {id: this.idTipoNovedad})
                    .then(res => {
                        this.mostrarNotificacion("Eliminar tipo novedad", res.data, "success");
                        this.datos.splice(this.datos.findIndex(x => x.id === this.idTipoNovedad), 1);
                    })
            },
            editarTipoNovedad(datos) {
                this.tipoNovedad.idTipoNovedad = datos.id;
                this.tipoNovedad.colorNovedad = datos.color;
                this.tipoNovedad.tipoNovedad = datos.nombre;
                this.tipoNovedad.validacbox = 1;
                this.poputActivo = true;
            }
        }
    }
</script>

<style scoped>

</style>