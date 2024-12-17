<template>
    <vs-row>
        <vs-col vs-sm="12" vs-md="12" vs-lg="12">
            <vx-card class="" title="Maestro de actividades">
                <div class="flex flex-wrap items-center mb-3">
                    <vs-button color="warning" type="filled" @click="reset">
                        Agregar actividad
                    </vs-button>
                    <vs-popup class="holamundo" title="Crear actividad" :active.sync="popupActivo">
                        <ValidationObserver ref="form">
                            <form @submit.prevent="guardarActividad">
                                <div class="vx-row">
                                    <div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 w-full mb-2">
                                        <validation-provider name="TIPO" rules="required"
                                                             v-slot="{ errors }">
                                            <vs-select autocomplete class="w-full" label="Tipo"
                                                       v-model="actividad.idTipo" style="width:100%"
                                                       @change="poputActivo=false">
                                                <vs-select-item :key="''" :value="''" :text="'---seleccione---'"/>
                                                <vs-select-item :key="index" :value="item.id" :text="item.nombre"
                                                                v-for="(item,index) in listaTipo"/>
                                            </vs-select>
                                            <span class="text-danger">{{ errors[0] }}</span>
                                        </validation-provider>
                                    </div>
                                    <div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 w-full mb-2">
                                        <validation-provider name="PREFIJO" rules="required"
                                                             v-slot="{ errors }">
                                            <vs-input label="Prefijo" class="w-full" :disabled="true"
                                                      v-model="actividad.codigoActividad"/>
                                            <span class="text-danger">{{ errors[0] }}</span>
                                        </validation-provider>
                                    </div>
                                </div>
                                <div class="vx-row">
                                    <div class="vx-col sm:w-12/12 md:w-12/12 lg:w-12/12 w-full mb-2">
                                        <validation-provider name="CATEGORIA" rules="required"
                                                             v-slot="{ errors }">
                                            <vs-select autocomplete class="w-full" label="Categoria"
                                                       v-model="actividad.idCategoria" style="width:100%">
                                                <vs-select-item :key="''" :value="''" :text="'---seleccione---'"/>
                                                <vs-select-item :key="index" :value="item.id" :text="item.nombre"
                                                                v-for="(item,index) in listaCategoria"/>
                                            </vs-select>
                                            <span class="text-danger">{{ errors[0] }}</span>
                                        </validation-provider>
                                    </div>
                                    <div class="vx-col sm:w-12/12 md:w-12/12 lg:w-12/12 w-full mb-2">
                                        <validation-provider name="TEMA" rules="required"
                                                             v-slot="{ errors }">
                                            <vs-select autocomplete class="w-full" label="Tema"
                                                       v-model="actividad.idTema" style="width:100%">
                                                <vs-select-item :key="''" :value="''" :text="'---seleccione---'"/>
                                                <vs-select-item :key="index" :value="item.id" :text="item.nombre"
                                                                v-for="(item,index) in listaTema"/>
                                            </vs-select>
                                            <span class="text-danger">{{ errors[0] }}</span>
                                        </validation-provider>
                                    </div>
                                    <div class="vx-col sm:w-12/12 md:w-12/12 lg:w-12/12 w-full mb-2">
                                        <validation-provider name="GRUPO" rules="required"
                                                             v-slot="{ errors }">
                                            <vs-select autocomplete class="w-full" label="Grupo"
                                                       v-model="actividad.idGrupo" style="width:100%">
                                                <vs-select-item :key="''" :value="''" :text="'---seleccione---'"/>
                                                <vs-select-item :key="index" :value="item.id" :text="item.nombre"
                                                                v-for="(item,index) in listaGrupo"/>
                                            </vs-select>
                                            <span class="text-danger">{{ errors[0] }}</span>
                                        </validation-provider>
                                    </div>
                                </div>
                                <div class="vx-row">
                                    <div class="vx-col sm:w-12/12 md:w-12/12 lg:w-12/12 w-full mb-2">
                                        <validation-provider name="NOMBRE ACTIVIDAD" rules="required"
                                                             v-slot="{ errors }">
                                            <vs-input label="Nombre actividad" v-model="actividad.actividad"
                                                      class="w-full"/>
                                            <span class="text-danger">{{ errors[0] }}</span>
                                        </validation-provider>
                                    </div>
                                    <div class="vx-col sm:w-6/12 md:w-6/12 lg:w-6/12 w-full mb-2">
                                        <label>Require informe</label>
                                        <validation-provider name="REQUIERE INFORME" rules="required"
                                                             v-slot="{ errors }">
                                            <vs-switch v-model="actividad.requiereInforme">
                                                <span slot="on">Sí</span>
                                                <span slot="off">No</span>
                                            </vs-switch>
                                            <span class="text-danger">{{ errors[0] }}</span>
                                        </validation-provider>
                                    </div>
                                    <div class="vx-col sm:w-6/12 md:w-6/12 lg:w-6/12 w-full mb-2"
                                         v-if="actividad.requiereInforme">
                                        <validation-provider name="TIEMPO INFORME" rules="required"
                                                             v-slot="{ errors }">
                                            <vs-input label="Tiempo informe" v-model="actividad.tiempoinforme"
                                                      class="w-full"/>
                                            <span class="text-danger">{{ errors[0] }}</span>
                                        </validation-provider>
                                    </div>
                                </div>
                                <div class="vx-row">
                                    <div class="vx-col w-full text-center">
                                        <boton texto_cargando="Guardando actividad..." type="submit"
                                               texto="Guardar actividad" :cargando="activeLoading"></boton>
                                    </div>
                                </div>
                            </form>
                        </ValidationObserver>
                    </vs-popup>
                </div>

                <vs-table :data="this.listaActividades" pagination :max-items="10" search>
                    <template slot="thead">
                        <vs-th>ID</vs-th>
                        <vs-th>Actividad</vs-th>
                        <vs-th>Prefijo</vs-th>
                        <vs-th>Und. Medida</vs-th>
                        <vs-th>Categoria</vs-th>
                        <vs-th>Tema</vs-th>
                        <vs-th>Tipo</vs-th>
                        <vs-th>Grupo</vs-th>
                        <vs-th>Acciones</vs-th>
                    </template>

                    <template slot-scope="{data}">
                        <vs-tr :key="indextr" v-for="(tr, indextr) in data">

                            <vs-td :data="data[indextr].idActividad">
                                {{ data[indextr].idActividad }}
                            </vs-td>

                            <vs-td :data="data[indextr].actividad">
                                {{ data[indextr].actividad }}
                            </vs-td>

                            <vs-td :data="data[indextr].codigoActividad">
                                {{ data[indextr].codigoActividad }}
                            </vs-td>

                            <vs-td :data="data[indextr].undMedida">
                                {{ data[indextr].undMedida }}
                            </vs-td>
                            <vs-td :data="data[indextr].categoria">
                                {{ data[indextr].categoria }}
                            </vs-td>
                            <vs-td :data="data[indextr].tema">
                                {{ data[indextr].tema }}
                            </vs-td>
                            <vs-td :data="data[indextr].tipo">
                                {{ data[indextr].tipo }}
                            </vs-td>
                            <vs-td :data="data[indextr].grupo">
                                {{ data[indextr].grupo }}
                            </vs-td>
                            <vs-td>
                                <vs-row vs-type="flex" vs-justify="flex-end">
                                    <vs-col vs-sm="6" vs-md="6" vs-lg="6" vs-type="flex" vs-justify="end"
                                            vs-align="end" vs-w="6">
                                        <vx-tooltip text="Editar actividad">
                                                <span @click="actualizarActividad(data[indextr])"
                                                      class="cursor-pointer"><i class="bx bx-edit-alt"></i></span>
                                        </vx-tooltip>

                                    </vs-col>
                                    <vs-col vs-sm="6" vs-md="6" vs-lg="6" vs-type="flex" vs-justify="end"
                                            vs-align="end" vs-w="6">
                                        <vx-tooltip text="Ver matriz de cualificación">
                                                <span @click="consultarMatrizCualificacion(data[indextr].idActividad,data[indextr].actividad)"
                                                      class="cursor-pointer"><i class="bx bx-add-to-queue"></i></span>
                                        </vx-tooltip>
                                    </vs-col>
                                </vs-row>
                            </vs-td>
                        </vs-tr>
                    </template>

                </vs-table>

            </vx-card>
        </vs-col>
        <vs-popup class="holamundo" :title="'Matriz de cualificación ('+actividadSeleccionada+')'"
                  :active.sync="popupMatrizActivo">
            <ValidationObserver ref="form">
                <form @submit.prevent="guardarGestorMatriz">
                    <div class="vx-row">
                        <div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 w-full mb-2">
                            <validation-provider name="GESTOR" rules="required"
                                                 v-slot="{ errors }">
                                <vs-select autocomplete class="w-full" label="Gestor"
                                           v-model="addGestorMatriz.idGestor" style="width:100%">
                                    <vs-select-item :key="''" :value="''" :text="'---seleccione---'"/>
                                    <vs-select-item :key="index" :value="item.id" :text="item.text"
                                                    v-for="(item,index) in tempListaGestores"/>
                                </vs-select>
                                <span class="text-danger">{{ errors[0] }}</span>
                            </validation-provider>
                        </div>
                        <div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 w-full mb-2">
                            <validation-provider name="CALIFICACION" rules="required"
                                                 v-slot="{ errors }">
                                <vs-select autocomplete class="w-full" label="Calificación"
                                           v-model="addGestorMatriz.calificacion" style="width:100%">
                                    <vs-select-item :key="''" :value="''" :text="'---seleccione---'"/>
                                    <vs-select-item :key="0" :value="0" :text="0"/>
                                    <vs-select-item :key="5" :value="5" :text="5"/>
                                    <vs-select-item :key="10" :value="10" :text="10"/>
                                </vs-select>
                                <span class="text-danger">{{ errors[0] }}</span>
                            </validation-provider>
                        </div>
                    </div>
                    <div class="vx-row">
                        <div class="vx-col w-full text-center">
                            <boton texto_cargando="Guardando gestor..." type="submit"
                                   texto="Guardar gestor" :cargando="activeLoading"></boton>
                        </div>
                    </div>
                </form>
            </ValidationObserver>
            <vs-table :data="this.datosMatrizActividad" pagination :max-items="10" search>
                <template slot="thead">
                    <vs-th>ID</vs-th>
                    <vs-th>Gestor</vs-th>
                    <vs-th>Calificación</vs-th>
                    <vs-th>Acciones</vs-th>
                </template>

                <template slot-scope="{data}">
                    <vs-tr :key="indextr" v-for="(tr, indextr) in data">

                        <vs-td :data="data[indextr].id">
                            {{ data[indextr].id }}
                        </vs-td>

                        <vs-td :data="data[indextr].gestor">
                            {{ data[indextr].gestor }}
                        </vs-td>
                        <vs-td :data="data[indextr].calificacion">
                            {{ data[indextr].calificacion }}
                        </vs-td>
                        <vs-td>
                            <vs-row vs-type="flex" vs-justify="flex-end">
                                <vs-col vs-sm="6" vs-md="6" vs-lg="6" vs-type="flex" vs-justify="end"
                                        vs-align="end" vs-w="6">
                                    <vx-tooltip text="Eliminar gestor">
                                                <span @click="eliminarGestor(data[indextr].id)"
                                                      class="cursor-pointer"><i class="bx bxs-tag-x"></i></span>
                                    </vx-tooltip>
                                </vs-col>
                            </vs-row>
                        </vs-td>
                    </vs-tr>
                </template>
            </vs-table>
        </vs-popup>
    </vs-row>
</template>

<script>
    export default {
        name: "actividades",
        data() {
            return {
                actividad: {
                    idActividad: 0,
                    actividad: null,
                    undMedida: null,
                    codigoActividad: null,
                    requiereInforme: false,
                    tiempoinforme: 0,
                    idCategoria: null,
                    idTema: null,
                    idTipo: null,
                    idGrupo: null
                },
                addGestorMatriz: {
                    idGestor: null,
                    idActividad: null,
                    calificacion: null
                },
                popupActivo: false,
                tipo: 'nuevo',
                actividadSeleccionada: '',
                popupMatrizActivo: false,
                listaCategoria: [],
                listaTema: [],
                listaTipo: [],
                listaGrupo: [],
                listaActividades: [],
                listaGestores: [],
                tempListaGestores: [],
                datosMatrizActividad: []
            }
        },
        created() {
            this.consultarActividades();
            this.consultarCombos();
            this.consultarGestores();
        },
        methods: {
            consultarCombos() {
                axios.post("/consultas/consultarCategoria")
                    .then(res => {
                        this.listaCategoria = res.data;
                    })
                axios.post("/consultas/consultarTema")
                    .then(res => {
                        this.listaTema = res.data;
                    })
                axios.post("/consultas/consultarTipo")
                    .then(res => {
                        this.listaTipo = res.data;
                    })
                axios.post("/consultas/consultarGrupo")
                    .then(res => {
                        this.listaGrupo = res.data;
                    })
            },
            guardarActividad() {
                this.activeLoading = true;
                axios.post("/maestros/guardarActividad", this.actividad)
                    .then(res => {
                        if (this.tipo == 'nuevo')
                            this.mostrarNotificacion("Registro exitoso!", "La actividad se ha registrado con exito!", "success");
                        else
                            this.mostrarNotificacion("Datos actualizados!", "La datos de la actividad se han actualizado con exito!", "success");
                        this.reset();
                        this.popupActivo = false;
                        this.activeLoading = false;
                        this.consultarActividades();
                    })
            },
            consultarActividades() {
                this.cargandoGeneral = true;
                axios.post("/maestros/consultarActividades")
                    .then(res => {
                        this.listaActividades = res.data;
                        this.cargandoGeneral = false;
                    })
            },
            reset() {
                this.actividad = {
                    idActividad: null,
                    actividad: null,
                    undMedida: null,
                    codigoActividad: null,
                    requiereInforme: false,
                    tiempoinforme: 0,
                    idCategoria: null,
                    idTema: null,
                    idTipo: null,
                    idGrupo: null
                };
                this.tipo = "nuevo";
                this.popupActivo = true;
            },
            actualizarActividad(datos) {
                this.actividad = {
                    idActividad: datos.idActividad,
                    actividad: datos.actividad,
                    undMedida: datos.undMedida,
                    codigoActividad: datos.codigoActividad,
                    requiereInforme: datos.requiereInforme,
                    tiempoinforme: datos.tiempoinforme,
                    idCategoria: datos.idCategoria,
                    idTema: datos.idTema,
                    idTipo: datos.idTipo,
                    idGrupo: datos.idGrupo
                };
                this.tipo="actualizar";
                this.popupActivo = true;
            },
            consultarMatrizCualificacion(id, actividad) {
                this.actividadSeleccionada = actividad;
                this.cargandoGeneral = true;
                this.addGestorMatriz.idActividad = id;
                axios.post("/maestros/consultarMatriz", {idActividad: id})
                    .then(res => {
                        this.datosMatrizActividad = res.data;
                        this.cargandoGeneral = false;
                        this.popupMatrizActivo = true;

                        var vm = this;
                        this.tempListaGestores = Object.assign([], this.listaGestores);
                        $.each(this.datosMatrizActividad, function (index, value) {
                            var existe = vm.tempListaGestores.findIndex(x => x.id == value.idGestor);
                            if (existe >= 0)
                                vm.tempListaGestores.splice(existe, 1);
                        })
                    });
            },
            eliminarGestor(id) {
                axios.post("/maestros/eliminarGestorMatriz", {"id": id})
                    .then(res => {
                        this.mostrarNotificacion("ELiminar gestor", "Se ha eliminado el gestor de la matriz de cualificación", 'success');
                        this.datosMatrizActividad.splice(this.datosMatrizActividad.findIndex(x => x.id == id), 1);
                    })
            },
            guardarGestorMatriz() {
                this.activeLoading = true;
                axios.post("/maestros/guardarGestorMatriz", this.addGestorMatriz)
                    .then(res => {
                        this.activeLoading = false;
                        this.mostrarNotificacion("Guardar gestor", "Se ha registro la calificación del gestor con exito!", "success");
                        this.consultarMatrizCualificacion(this.addGestorMatriz.idActividad, this.actividadSeleccionada);
                        this.addGestorMatriz = {
                            idGestor: null,
                            idActividad: this.addGestorMatriz.idActividad,
                            calificacion: null
                        };
                    })
            },
            consultarGestores() {
                axios.post("/reservas/listaGestores")
                    .then(res => {
                        this.listaGestores = res.data;
                    })
            },
            getNumbersInString(string) {
                var tmp = string.split("");
                var map = tmp.map(function (current) {
                    if (!isNaN(parseInt(current))) {
                        return current;
                    }
                });

                var numbers = map.filter(function (value) {
                    return value != undefined;
                });

                return numbers.join("");
            }
        },
        watch: {
            'actividad.idTipo': {
                handler: function handler(after, before) {
                    var prefijo = this.listaTipo.find(x => x.id == this.actividad.idTipo);
                    var temp_actividad = this.listaActividades[this.listaActividades.length - 1];
                    var numeros = [];
                    var vm = this;
                    $.each(this.listaActividades, function (index, value) {
                        numeros.push(parseInt(vm.getNumbersInString(value.codigoActividad)));
                    })
                    this.actividad.codigoActividad = prefijo.prefijo + (Math.max.apply(null, numeros) + 1);
                },
                deep: true
            },
        }
    }
</script>

<style scoped>

</style>