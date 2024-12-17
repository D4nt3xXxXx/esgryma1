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

        <vs-popup classContent="popup-example poputSecundario"
                  :title="'Subir informe'"
                  :active.sync="abrirPoputDoc">
            <subir-documento @actualizarVista="changeVista" :datos-reserva="orden"></subir-documento>
        </vs-popup>
        <vs-popup :title="'Previsualización documento'" fullscreen :active.sync="abrirPoputVerDoc">
            <vue-friendly-iframe :src="viewArchivo" :style="{'height':ancho,'width':'100%' }"
                                 className="w-100 iframeArchivo"
                                 @load="loadArchivo" frameborder="0" gesture="media"
                                 allow="encrypted-media"></vue-friendly-iframe>
        </vs-popup>
        <vs-tabs>
            <vs-tab label="O.T">
                <div class="con-tab-ejemplo">
                    <div class="vx-row">
                        <div class="vx-col sm:w-3/3 w-full mb-4">
                            <vx-card class="" title="Información de la reserva:">
                                <div class="vx-row mb-4 mt-3">
                                    <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full mb-1">
                                        <strong>Empresa: </strong>{{unicareserva.empresaOrden}}
                                    </div>
                                    <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full mb-2">
                                        <strong>Vigencia: </strong>
                                    </div>
                                    <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full mb-2">
                                        <strong>Gestor sugerido: </strong>{{unicareserva.gestorSolicitado}}
                                    </div>
                                </div>
                                <div class="vx-row mb-4 mt-6">
                                    <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full mb-1">
                                        <strong>Orden de servicio: </strong>{{unicareserva.nroOrden}}
                                    </div>
                                    <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full mb-2">
                                        <strong>Tema: </strong>{{unicareserva.temaOrden}}
                                    </div>
                                    <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full mb-2">
                                        <strong>Unidades: </strong>{{unicareserva.unidadesOrden}} hora(s)
                                    </div>
                                </div>
                                <div class="vx-row mb-4 mt-6">
                                    <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full mb-1 pt-3 pb-3">
                                        <strong>Estado: </strong>{{unicareserva.nomEstado}}
                                    </div>
                                    <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full mb-1">
                                        <strong>Cliente: </strong>{{unicareserva.nombreCliente}}
                                    </div>
                                </div>
                                <div class="vx-row mb-4 mt-6">
                                    <div class="vx-col sm:w-12/12 w-full mb-1 pt-3 pb-3" style='border:2px solid #777;'>
                                        <strong>Observaciones: </strong>{{unicareserva.obsOrden}}
                                    </div>
                                </div>
                            </vx-card>
                        </div>
                    </div>
                    <div class="vx-row">
                        <div class="vx-col sm:w-3/3 w-full mb-2">

                            <vx-card title="Información de la O.T">
                                <ValidationObserver ref="form">
                                    <form @submit.prevent="guardarOrden">
                                        <div class="vx-row">
                                            <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full mb-2">
                                                <span>Tipo actividad</span>
                                                <vs-select autocomplete class="form-control"
                                                           @change="obtenerActividades"
                                                           v-model="orden.tipoActividad" style="width:100%">
                                                    <vs-select-item :key="''" :value="''" :text="'---seleccione---'"/>
                                                    <vs-select-item :key="index" :value="item.id" :text="item.text"
                                                                    v-for="(item,index) in listaTipoActividad"/>
                                                </vs-select>
                                            </div>
                                            <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full mb-2">
                                                <vs-input label="Categoria" class="w-full"
                                                          label-placeholder="Categoria..."
                                                          v-model="orden.categoria" :disabled="true"/>
                                            </div>
                                            <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full mb-2">
                                                <vs-input label="Tema" class="w-full"
                                                          label-placeholder="Tema..."
                                                          v-model="orden.tema" :disabled="true"/>
                                            </div>
                                            <div class="vx-col sm:w-12/12 md:w-9/12 lg:w-9/12 w-full mb-2">
                                                <span>Actividad</span>
                                                <vs-select autocomplete class="selectExample"
                                                           v-model="orden.idActividad"
                                                           @change="obtenerDatosActividad($event)"
                                                           style="width:100%">
                                                    <vs-select-item :key="''" :value="''" :text="'---seleccione---'"/>
                                                    <vs-select-item :key="index" :value="item.id" :text="item.text"
                                                                    v-for="(item,index) in listaActividad"/>
                                                </vs-select>
                                            </div>
                                            <div class="vx-col sm:w-12/12 md:w-3/12 lg:w-3/12 w-full mb-2">
                                                <span>Ciudad</span>
                                                <vs-select autocomplete class="selectExample"
                                                           v-model="orden.ciudad"
                                                           style="width:100%">
                                                    <vs-select-item :key="''" :value="''" :text="'---seleccione---'"/>
                                                    <vs-select-item :key="index" :value="item.id" :text="item.text"
                                                                    v-for="(item,index) in listaCiudades"/>
                                                </vs-select>
                                            </div>
                                        </div>


                                        <div class="vx-row">
                                            <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full mb-2">
                                                <validation-provider name="nroParticiones" rules="required|numeric"
                                                                     v-slot="{ errors }">
                                                    <vs-input class="w-full" label-placeholder="Tiempo de la orden"
                                                              v-model="unicareserva.unidadesOrden"/>
                                                    <span class="text-danger">{{ errors[0] }}</span>
                                                </validation-provider>
                                            </div>
                                            <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full mb-2">
                                                <vs-row>
                                                    <vs-col vs-sm="11" vs-md="11" vs-lg="11">
                                                        <span>Gestor</span>
                                                        <validation-provider name="GESTOR" rules="required"
                                                                             v-slot="{ errors }">
                                                            <vs-select autocomplete class="selectExample"
                                                                       v-model="orden.idGestor"
                                                                       @change="getAgendaGestor($event)"
                                                                       style="width:100%">
                                                                <vs-select-item :key="''" :value="''"
                                                                                :text="'---seleccione---'"/>
                                                                <vs-select-item :key="index" :value="item.id"
                                                                                :text="item.text"
                                                                                v-for="(item,index) in listaGestores"/>
                                                            </vs-select>
                                                            <span class="text-danger">{{ errors[0] }}</span>
                                                        </validation-provider>
                                                    </vs-col>
                                                    <vs-col vs-sm="1" vs-md="1" vs-lg="1" vs-align="flex-end"
                                                            v-if="orden.idGestor>0"
                                                            vs-type="flex" vs-justify="space-between">
                                                        <a target="_blank" class="text-info"
                                                           :href="'/app/agenda?idGestor='+orden.idGestor">
                                                            <i class="material-icons">event_note</i>
                                                        </a>
                                                    </vs-col>
                                                </vs-row>
                                            </div>
                                            <div class="vx-col sm:w-12/12 md:w-2/12 lg:w-2/12 w-full mb-2">
                                                <span>Fecha actividad</span>
                                                <datetime type="datetime"
                                                          input-class="vs-inputx vs-input--input normal"
                                                          value-zone="America/Bogota"
                                                          zone="America/Bogota" v-model="orden.fechaActividad"
                                                          format="yyyy-MM-dd H:m"
                                                          auto></datetime>
                                            </div>
                                            <div class="vx-col sm:w-12/12 md:w-2/12 lg:w-2/12 w-full mb-2">
                                                <span>Fecha fin actividad</span>
                                                <datetime type="datetime"
                                                          input-class="vs-inputx vs-input--input normal"
                                                          value-zone="America/Bogota"
                                                          zone="America/Bogota" v-model="orden.fechaFinActividad"
                                                          format="yyyy-MM-dd H:m"
                                                          :min-datetime="orden.fechaActividad"
                                                          auto></datetime>
                                            </div>
                                            <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full mb-2">
                                                <vs-row>
                                                    <vs-col vs-sm="11" vs-md="11" vs-lg="11">
                                                        <span>Salones disponibles</span>
                                                        <vs-select autocomplete class="selectExample"
                                                                   v-model="orden.idSalon"
                                                                   @change="getAgendaSalon($event)" style="width:100%">
                                                            <vs-select-item :key="''" :value="''"
                                                                            :text="'---seleccione---'"/>
                                                            <vs-select-item :key="index" :value="item.id"
                                                                            :text="item.text"
                                                                            v-for="(item,index) in listaSalon"/>
                                                        </vs-select>
                                                    </vs-col>
                                                    <vs-col vs-sm="1" vs-md="1" vs-lg="1" vs-align="flex-end"
                                                            v-if="orden.idGestor>0"
                                                            vs-type="flex" vs-justify="space-between">
                                                        <a target="_blank" class="text-info"
                                                           :href="'/app/salones?idSalon='+orden.idSalon">
                                                            <i class="material-icons">event_note</i>
                                                        </a>
                                                    </vs-col>
                                                </vs-row>
                                            </div>
                                            <div class="vx-col sm:w-1/3 w-full mb-2"
                                                 v-if="requiereInforme">
                                                <span>Fecha informe</span>
                                                <datetime :min-datetime="orden.fechaActividad" type="date"
                                                          input-class="vs-inputx vs-input--input normal"
                                                          value-zone="America/Bogota"
                                                          zone="America/Bogota" v-model="orden.fechaInforme"
                                                          format="yyyy-MM-dd"
                                                          auto></datetime>
                                            </div>
                                            <div class="vx-col sm:w-1/3 w-full mb-2">
                                                <vs-input class="w-full" label-placeholder="Observaciones"
                                                          v-model="orden.observaciones"/>
                                            </div>
                                        </div>
                                        <div class="vx-row">
                                            <div class="vx-col sm:w-1/3 w-full mb-2">
                                                <vs-input class="w-full" label-placeholder="Nombre contacto"
                                                          v-model="orden.nombreContacto"/>
                                            </div>
                                            <div class="vx-col sm:w-12/12 md:w-2/12 lg:w-2/12 w-full mb-2">
                                                <validation-provider name="TELEFONO CONTACTO" rules="numeric|max:10"
                                                                     v-slot="{ errors }">
                                                    <vs-input class="w-full" v-model="orden.telefonocontacto"
                                                              label-placeholder="Teléfono contacto"/>
                                                    <span class="text-danger">{{ errors[0] }}</span>
                                                </validation-provider>
                                            </div>
                                            <div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 w-full mb-2">
                                                <vs-input class="w-full" label-placeholder="Dirección"
                                                          v-model="orden.direccion"/>
                                            </div>
                                            <div class="vx-col sm:w-1/3 w-full mb-2">
                                                <vs-input class="w-full" label-placeholder="Asesor ARL"
                                                          v-model="orden.asesorARL"/>
                                            </div>
                                            <!--<div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full mb-2"
                                                 v-if="requiereInforme">

                                                <validation-provider name="URL INFORME"
                                                                     :rules="{ regex: /^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:/?#[\]@!\$&'\(\)\*\+,;=.]+$/ }"
                                                                     v-slot="{ errors }">
                                                    <vs-input id="InformeInput" class="w-full Informe"
                                                              label-placeholder="URL informe"
                                                              v-model="orden.urlInforme"/>
                                                    <span class="text-danger">{{ errors[0] }}</span>
                                                </validation-provider>
                                            </div>-->
                                            <div class="vx-col sm:w-1/3 w-full mb-2">
                                                <vs-input class="w-full" label-placeholder="ID curso"
                                                          v-model="orden.idcurso"/>
                                            </div>
                                            <div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 w-full mb-4 text-center"
                                                 v-if="orden.idEstadoReserva==13 || orden.idEstadoReserva==14">
                                                <a :href="urlEncuenta" target="_blank" class="w-full">
                                                    << Realizar encuesta de satisfacción >>
                                                </a>
                                            </div>
                                            <!--<div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 w-full mb-4 text-center"
                                                 v-if="orden.idEstadoReserva==13 || orden.idEstadoReserva==14">
                                                <a :href="urlInforme" target="_blank" class="w-full">
                                                    << Subir informe >>
                                                </a>
                                            </div>-->
                                            <div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 w-full mb-4 text-center"
                                                 v-if="orden.idEstadoReserva==13 || orden.idEstadoReserva==14">
                                                <a href="#" @click="abrirPoputDoc=true" class="w-full">
                                                    << Subir informe >>
                                                </a>
                                            </div>
                                            <div class="vx-col sm:w-12/12 md:w-12/12 lg:w-12/12 w-full mb-2"
                                                 v-if="datosDocumentos.length>0">
                                                <vs-list>
                                                    <vs-list-item v-for="item in datosDocumentos" :title="item.nombre"
                                                                  :subtitle="diaSemana2(item.created_at)">
                                                        <span style="cursor: pointer" @click.prevent="verDocumento(item)"
                                                              class="material-icons text-info">visibility</span>
                                                        <span style="cursor: pointer" @click.prevent="descargarDcto(item.path,item.nombre,item.ext)"
                                                              class="material-icons text-success">cloud_download</span>
                                                    </vs-list-item>
                                                </vs-list>
                                            </div>
                                        </div>
                                        <vs-row vs-type="flex" vs-justify="flex-end"
                                                v-if="(orden.idEstadoOT==16 || orden.idEstadoReserva==17) && isRole('admin|asisadministrativo')">
                                            <vs-col vs-sm="12" vs-md="3" vs-lg="3" v-if="orden.idEstadoReserva==17"
                                                    vs-type="flex" vs-justify="center"
                                                    vs-align="center">
                                                <strong>FACTURADA</strong>
                                                <vs-switch color="success" v-model="orden.facturada"
                                                           @input="facturarOT">
                                                    <span slot="on">Aceptar</span>
                                                    <span slot="off">NO</span>
                                                </vs-switch>
                                            </vs-col>
                                        </vs-row>
                                        <vs-row vs-type="flex" vs-justify="flex-end"
                                                v-if="orden.idEstadoOT!=16  && orden.idEstadoOT!=8 && orden.idGestor==$store.state.esgryma.id">
                                            <vs-col vs-sm="12" vs-md="3" vs-lg="3" v-if="canPermiso('ot.infrevisado')"
                                                    vs-type="flex"
                                                    vs-justify="center" vs-align="center">
                                                <strong>INF. REVISADO</strong>
                                                <vs-switch color="success" v-model="orden.infrevisado">
                                                    <span slot="on">Aceptar</span>
                                                    <span slot="off">NO</span>
                                                </vs-switch>
                                            </vs-col>
                                            <vs-col vs-sm="12" vs-md="4" vs-lg="4"
                                                    v-if="isRole('admin|gestor') && orden.idEstadoReserva==14"
                                                    vs-type="flex"
                                                    vs-justify="center" vs-align="center">
                                                <strong>Encuesta realizada ?</strong>
                                                <vs-switch class="estados" color="success" @input="actualizarEncuesta"
                                                           v-model="orden.encuestaOk">
                                                    <span slot="on">SI</span>
                                                    <span slot="off">NO</span>
                                                </vs-switch>
                                            </vs-col>
                                            <!--<vs-col vs-sm="12" vs-md="4" vs-lg="3"
                                                    v-if="isRole('admin|gestor') && orden.idEstadoReserva==14 && requiereInforme"
                                                    vs-type="flex"
                                                    vs-justify="center" vs-align="center">
                                                <strong>Informe enviado ?</strong>
                                                <vs-switch class="estados" color="success" @input="actualizarInforme"
                                                           v-model="orden.informeOk">
                                                    <span slot="on">SI</span>
                                                    <span slot="off">NO</span>
                                                </vs-switch>
                                            </vs-col>-->
                                        </vs-row>

                                        <div class="vx-row mt-3"
                                             v-if="!isRole('gestor') && (orden.idEstadoOT==4 || orden.idEstadoOT==5 || orden.idEstadoOT==12)">
                                            <div class="vx-col w-full text-center">
                                                &nbsp;<vs-button v-if="orden.idEstadoOT!=5"
                                                                 :loading="loading"
                                                                 :active="loading"
                                                                 @click="guardarOrden">
                                                Guardar
                                            </vs-button>
                                                <vs-button
                                                    v-if="isRole('admin|asisadministrativo') && (orden.idEstadoOT==4 || orden.idEstadoOT==12 || orden.idEstadoOT==5)"
                                                    color="success"
                                                    type="filled" v-on:click="programarOrden()">
                                                    {{orden.idEstadoOT==5 ? 'Guardar' : 'Programar O.T'}}
                                                </vs-button>
                                                <vs-button
                                                    v-if="orden.idOT!=null && isRole('admin|asisadministrativo') && (orden.idEstadoReserva==14 || orden.idEstadoReserva==3 || orden.idEstadoOT==5)"
                                                    @click="cancelarOT"
                                                    color="danger" type="filled">
                                                    Cancelar ot
                                                </vs-button>
                                            </div>
                                        </div>
                                        <div
                                            v-else-if="isRole('admin|gestor|coordprevencion') && orden.idEstadoOT!=16 && orden.idEstadoOT!=8">
                                            <vs-button color="success"
                                                       v-if="requiereInforme() && isRole('admin|asisadministrativo')"
                                                       type="filled" v-on:click="programarOrden()">
                                                Guardar
                                            </vs-button>
                                            <vs-button
                                                v-if="(orden.idEstadoOT==5 || orden.idEstadoOT==6 || orden.idEstadoOT==7) && isRole('admin|gestor')"
                                                gradient
                                                :loading="loading"
                                                @click="guardarOrdenRealizada('otro')"
                                            >
                                                Guardar O.T realizada
                                            </vs-button>
                                            <vs-button v-if="isRole('admin|coordprevencion') && orden.idEstadoOT==7"
                                                       color="danger" type="gradient"
                                                       :loading="loading"
                                                       @click="guardarOrdenRealizada('revision')"
                                            >
                                                Guardar revisión
                                            </vs-button>
                                        </div>
                                    </form>
                                </ValidationObserver>
                            </vx-card>
                        </div>
                    </div>
                </div>
            </vs-tab>
            <vs-tab label="Participantes"
                    :disabled="(orden.tipoActividad!=1 && orden.tipoActividad!=4) || isRole('gestor')">
                <vs-row vs-type="flex" vs-justify="flex-end">
                    <vs-col vs-sm="12" vs-md="12" vs-lg="12" vs-type="flex" vs-justify="end" vs-align="end" vs-w="2">
                        <vs-button
                            icon
                            color="success"
                            flat
                            type="flat"
                            @click="nuevoparticipante=(nuevoparticipante ? false : true)"
                        >
                            <i class='material-icons'>person_add</i>
                        </vs-button>
                    </vs-col>
                </vs-row>
                <vs-card v-show="nuevoparticipante">
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
                    <vs-table :data="datosParticipantes">

                        <template slot="thead">
                            <vs-th>ID</vs-th>
                            <vs-th>Cedúla</vs-th>
                            <vs-th>Nombre</vs-th>
                            <vs-th>Empresa</vs-th>
                            <vs-th>Tipo</vs-th>
                            <vs-th>Facturado</vs-th>
                            <vs-th></vs-th>
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
                                    <vs-switch v-model="tr.facturado" @input="actualizarFacturado(tr)">
                                        <span slot="on">SI</span>
                                        <span slot="off">NO</span>
                                    </vs-switch>
                                </vs-td>
                                <vs-td>
                                    <vs-button @click="eliminarParticipante(tr)" v-if="tr.facturado==0" color="danger"
                                               type="gradient"
                                               icon="delete_forever"></vs-button>
                                </vs-td>
                            </vs-tr>
                        </template>
                    </vs-table>
                </vs-card>
            </vs-tab>
            <vs-tab label="Bitacora" :disabled="!orden.idOT">
                <bitacora ref="componetBitacora" :datosBitacora="datosBitacora" :datos="unicareserva"></bitacora>
            </vs-tab>
        </vs-tabs>


    </div>
</template>

<script>
    import {DateTime} from 'luxon';
    import Participantes from './participantes'
    import Bitacora from './bitacora';
    import SubirDocumento from "../documentos/subirDocumento";
    import VueFriendlyIframe from 'vue-friendly-iframe';

    export default {
        name: "orden_edit",
        props: {
            idReserva: {
                required: true,
            }
        },
        components: {
            SubirDocumento,
            Participantes, Bitacora
        },
        component(id) {
            VueFriendlyIframe
        },
        data() {
            return {
                unicareserva: [],
                orden: {
                    idGestor: null,
                    idReserva: null,
                    idCategoria: null,
                    categoria: null,
                    idActividad: null,
                    fechaActividad: null,
                    fechaFinActividad: null,
                    asesorARL: null,
                    tipoActividad: null,
                    tiempoEjecutar: null,
                    fechaInforme: null,
                    direccion: null,
                    coordDireccion: null,
                    nombreContacto: null,
                    estadoOT: 20,
                    observaciones: null,
                    facturada: false,
                    infrevisado: false,
                    urlInforme: null,
                    idEstadoOT: 4,
                    idSalon: null,
                    idTema: null,
                    tema: null,
                    telefonocontacto: null,
                    idEstadoReserva: null,
                    idOT: null,
                    ciudad: null,
                    encuestaOk: false,
                    informeOk: false,
                    idcurso: null
                },
                participante: {
                    cedula: null,
                    nombre: null,
                    empresa: null,
                    tipo: null,
                    facturado: 0,
                    idOT: null,
                    estado: 'Nuevo'
                },
                nuevoparticipante: false,
                listaHoras: [],
                nroParticiones: null,
                listaParticiones: [],
                listaCategoria: [],
                listaActividad: [],
                listaGestores: [],
                listaSalon: [],
                listaTipoActividad: [],
                listaTemas: [],
                agendaGestor: false,
                agendaSalon: false,
                abrirBitacora: false,
                datosParticipantes: [],
                listaCiudades: [],
                datosBitacora: [],
                datosDocumentos: [],
                fechaActual: null,
                urlEncuenta: null,
                urlInforme: null,
                abrirPoputDoc: false,
                abrirPoputVerDoc: false,
                viewArchivo: '',
                ancho: 0,
            }
        },
        computed: {
            validarEncuestaSinInforme() {
                if (!this.requiereInforme() /*&& this.orden.encuestaOk*/)
                    return true;
                /*else if (this.orden.encuestaOk)
                    return true;*/
                return false;
            },
        },
        created() {
            this.fechaActual = DateTime.local();
        },

        mounted() {
            if (this.orden.idEstadoOT == 16) {
                $("#page-user-edit .input-switch").each(function (index, elem) {
                    $(this).prop("disabled", true);
                })
            }
            ;

        },
        beforeUpdate() {
            this.ancho = this.tamanoVentana()[1] - 160;
        },
        methods: {
            eventoTodo() {
                if (this.isRole('gestor')) {
                    $("#page-user-edit .vs-inputx").each(function (index, elem) {
                        if ($(this).attr("id") != 'InformeInput')
                            $(this).prop("disabled", true);
                    })
                    $("#page-user-edit .input-select").each(function (index, elem) {
                        $(this).prop("disabled", true);
                    })
                }
                if (this.orden.idEstadoOT == 16) {
                    $("#page-user-edit .input-switch").each(function (index, elem) {
                        $(this).prop("disabled", true);
                    })
                }
                this.listaCiudades = [{id: "CARTAGENA", text: 'CARTAGENA'}, {id: "MONTERIA", text: 'MONTERIA'}];
            },
            remarcaHoras(orden, tiempo) {
                for (var i = 0; i < this.nroParticiones; i++) {
                    if (this.listaParticiones[i].nroOrden = orden) {
                        this.listaParticiones[i].horas = tiempo;
                    }
                }
            },
            particionarReserva() {
                this.listaParticiones = [];
                for (var i = 1; i <= this.nroParticiones; i++) {
                    this.listaParticiones.push({
                        nroOrden: this.unicareserva[0].nroOrden + '-' + i,
                        horas: this.unicareserva[0].unidadesOrden / this.nroParticiones
                    });
                }
                // console.log(this.listaParticiones);
            },

            obtenerreservaId(id) {
                if ((id != null && id != undefined) && (this.idReserva != null && this.idReserva != undefined)) {
                    this.listaSalones();
                    this.obtenerTipoActividad();
                    this.cargandoGeneral = true;
                    axios.post("/reservas/listareservaId", {idReserva: this.idReserva || id})
                        .then(res => {
                            this.unicareserva = res.data[0];
                            for (var i = 1; i <= this.unicareserva.unidadesOrdenTotal; i++) {
                                this.listaHoras.push({id: i, text: i});
                            }
                            this.nroParticiones = this.listaHoras.length > 0 ? this.listaHoras[0].id : 0;
                            this.orden.idReserva = this.idReserva;
                            this.orden.tipoActividad = this.unicareserva.tipoActividad;
                            this.orden.idActividad = this.unicareserva.idActividad;
                            this.orden.idGestor = this.unicareserva.idGestor;
                            this.orden.fechaActividad = this.unicareserva.fechaActividad != null ? DateTime.fromSQL(this.unicareserva.fechaActividad) : null;
                            this.orden.idSalon = this.unicareserva.idSalon;
                            this.orden.observaciones = this.unicareserva.observaciones;
                            this.orden.nombreContacto = this.unicareserva.nombreContacto;
                            this.orden.idEstadoReserva = this.unicareserva.idEstadoReserva;
                            this.orden.idOT = this.unicareserva.idOT;
                            this.orden.fechaInforme = this.unicareserva.fechaInforme != null ? DateTime.fromSQL(this.unicareserva.fechaInforme) : null;
                            this.orden.observaciones = this.unicareserva.observaciones;
                            this.orden.nombreContacto = this.unicareserva.nombreContacto;
                            this.orden.telefonocontacto = this.unicareserva.telContacto;
                            this.orden.direccion = this.unicareserva.direccion;
                            this.orden.asesorARL = this.unicareserva.asesorARL;
                            this.orden.urlInforme = this.unicareserva.urlInforme;
                            this.orden.ciudad = this.unicareserva.ciudad;
                            this.orden.encuestaOk = this.unicareserva.encuestaOK;
                            this.orden.informeOk = this.unicareserva.informeOK;
                            this.orden.idEstadoOT = this.unicareserva.idEstadoOT || 4;
                            this.orden.idcurso = this.unicareserva.idcurso;
                            this.orden.fechaFinActividad = this.unicareserva.fechaFinActividad != null ? DateTime.fromSQL(this.unicareserva.fechaFinActividad) : null;
                            this.cargandoGeneral = false;
                            if (this.orden.idOT != null && this.orden.idOT != '') {
                                this.consultarDoc();
                                this.consultarParticipantes(this.orden.idOT);
                                this.listarBitacoras();
                                if (this.orden.idEstadoReserva == 13 || this.orden.idEstadoReserva == 14) {
                                    this.consultarEncuenta();
                                    this.consultarInforme();
                                }
                            }
                            this.eventoTodo();
                        }, err => {
                            this.cargandoGeneral = false;
                        })
                }
            },
            consultarDoc() {
                axios.post("/documentos/consultarDocumentos", {
                    idReserva: this.orden.idReserva,
                    idOt: this.orden.idOT,
                    idGestor: this.orden.idGestor
                })
                    .then(res => {
                        this.datosDocumentos = res.data;
                    })
            },
            listarBitacoras() {
                axios.post("/ordenes/listarBitacoras", {idOT: this.unicareserva.idOT})
                    .then(res => {
                        this.datosBitacora = res.data;
                    })
            },
            obtenerActividades(val) {
                // console.log('Change on categorias: '+val);
                axios.post("/ordenes/listaActividades", {idTipoActividad: this.orden.tipoActividad})
                    .then(res => {
                        this.listaActividad = res.data;
                        this.obtenerDatosActividad();
                    })
            },

            guardarOrden() {
                this.$refs.form.validate().then(success => {
                    if (!success) {
                        return;
                    }
                    this.orden.idReserva = this.unicareserva.idReserva;
                    this.orden.tiempoEjecutar = this.unicareserva.unidadesOrden;
                    //this.orden.tipoActividad = this.unicareserva.idTipo;
                    var res_fecha = this.validarFechas();
                    if (this.orden.idGestor == null) {
                        this.$vs.notify({
                            title: 'Error',
                            text: 'Debe escojer minimo un gestor para dejar la orden en gestión.',
                            color: 'warning'
                        });
                    } else if (res_fecha != 'ok') {
                        this.$vs.notify({
                            title: 'Error',
                            text: res_fecha,
                            color: 'warning'
                        });
                    } else {
                        this.datosGuardarOrden().then(res => {
                            if (res == 'ok') {
                                this.$vs.notify({
                                    title: 'Registro exitoso',
                                    text: 'La reserva se encuentra en GESTION!',
                                    color: 'success'
                                });
                                location.reload();
                            } else {
                                this.$vs.notify({
                                    title: 'Registro fallido',
                                    text: res,
                                    color: 'warning'
                                });
                            }
                        });

                    }
                });
            },
            programarOt() {
                this.orden.idReserva = this.unicareserva.idReserva;
                this.orden.tiempoEjecutar = this.unicareserva.unidadesOrden;
                this.datosGuardarOrden().then(result => {
                    if (result == 'ok') {
                        axios.post("/ordenes/programarOt", {
                            idReserva: this.unicareserva.idReserva,
                            idOt: this.unicareserva.idOT,
                            participantes: this.datosParticipantes
                        })
                            .then(res => {
                                this.mostrarNotificacion("Datos actualizados", "Se ha programado la O.T con exito!", "success")
                                location.reload();
                            })
                    } else {
                        this.mostrarNotificacion("Error:::", result, "warn");
                    }
                })

            },
            async datosGuardarOrden() {
                const res = await axios.post('/ordenes/GuardarOrden', {
                    orden: this.orden,
                    participantes: this.datosParticipantes
                });
                return res.data;
            },
            obtenerTipoActividad() {
                axios.post("/ordenes/tipoActividad")
                    .then(res => {
                        this.listaTipoActividad = res.data;
                    })
            },
            obtenerGestores(val) {
                axios.post("/ordenes/listaGestores", {idActividad: val})
                    .then(res => {
                        this.listaGestores = res.data;
                    })
            },
            listaCategorias(id) {
                axios.post("/ordenes/listaCategoria", {idCategoria: id})
                    .then(res => {
                        this.listaCategoria = res.data;
                        this.orden.categoria = this.listaCategoria[0].text;
                        this.orden.idCategoria = this.listaCategoria[0].id;
                    });
                console.log('listando categorias');
            },
            listaSalones() {
                axios.post("/ordenes/listaSalon")
                    .then(res => {
                        this.listaSalon = res.data;
                    });
                console.log('listando salones');
            },
            programarOrden() {
                var res_fecha = this.validarFechas();
                if (this.orden.idGestor == null) {
                    this.$vs.notify({
                        title: 'Error',
                        text: 'Debe escojer un gestor.',
                        color: 'warning'
                    });
                } else if (this.orden.fechaActividad == null || this.orden.fechaActividad == '') {
                    this.$vs.notify({
                        title: 'Error',
                        text: 'Debe escojer una fecha para la actividad.',
                        color: 'warning'
                    });
                } else if (this.orden.tipoActividad == 1 && this.datosParticipantes.length == 0) {
                    this.$vs.notify({
                        title: 'Error',
                        text: 'Debe agregar por lo menos un participante al la O.T!',
                        color: 'warning'
                    });
                } else if (res_fecha != 'ok') {
                    this.mostrarNotificacion("Error", res_fecha, 'danger')
                } else {
                    this.orden.idEstadoOT = 5;
                    this.orden.estadoOT = 21;
                    this.programarOt();
                }
            },

            getAgendaGestor(id) {
                this.agendaGestor = true;
                this.agendaSalon = false;
            },
            getAgendaSalon(id) {
                this.agendaSalon = true;
                this.agendaGestor = false;
            },
            obtenerDatosActividad() {
                if (this.orden.idActividad != null) {
                    this.obtenerGestores(this.orden.idActividad);
                    const datos = this.listaActividad.find(x => x.id == this.orden.idActividad);
                    const idTema = datos.idTema;
                    const idCategoria = datos.idCategoria;

                    this.listaCategorias(idCategoria);
                    this.obtenerTemas(idTema);
                }
            },
            obtenerTemas(id) {
                this.cargandoGeneral = true;
                axios.post("/ordenes/listarTema", {idTema: id})
                    .then(res => {
                        this.listaTemas = res.data;
                        this.orden.idTema = this.listaTemas[0].id;
                        this.orden.tema = this.listaTemas[0].text;
                        this.cargandoGeneral = false;
                    });
            },
            consultarParticipantes(id) {
                axios.post("/ordenes/listarParticiapantes", {idOt: id})
                    .then(res => {
                        this.datosParticipantes = res.data;
                    });
            },
            agregarParticiapante() {
                const existe = this.datosParticipantes.findIndex(x => x.cedula == this.participante.cedula);
                if (existe < 0) {
                    this.datosParticipantes.push(Object.assign({}, this.participante));
                    this.participante.cedula = null;
                    this.participante.nombre = null;
                    this.participante.empresa = null;
                    this.participante.tipo = null;
                    this.participante.facturado = 0;
                    this.participante.estado = 'Nuevo';
                } else
                    this.$vs.notify({
                        title: 'Participante existente',
                        text: 'El participante ya existe, por favor valide la información!',
                        color: 'warning'
                    });
            },
            eliminarParticipante(datos) {
                if (datos.estado == 'Nuevo') {
                    this.datosParticipantes.splice(this.datosParticipantes.findIndex(x => x.cedula == datos.cedula), 1);
                } else {
                    if (datos.facturado == 1) {
                        this.$vs.notify({
                            title: 'Participante facturado',
                            text: 'El participante no se puede eliminar porque se encuentra facturado!',
                            color: 'warning'
                        });
                    } else {
                        this.cargandoGeneral = true;
                        axios.post("/ordenes/eliminarParticipante", {idParticipante: datos.idParticipante})
                            .then(res => {
                                this.cargandoGeneral = false;
                                this.datosParticipantes.splice(this.datosParticipantes.findIndex(x => x.cedula == datos.cedula), 1);
                            });
                    }
                }
            },
            actualizarFacturado(e) {
                axios.post("/ordenes/actualizarFacturado", e)
                    .then(res => {
                        this.mostrarNotificacion("Registro exitoso", "Se ha actualizado el registro con exito!", 'success');
                    })
            },
            abrirBitacoraPoput() {
                if (this.unicareserva.idOT != null && this.unicareserva.idOT != '') {
                    if (this.abrirBitacora == false) {
                        this.abrirBitacora = true;
                        this.$emit("cerrarPoput", false);
                    } else {
                        this.abrirBitacora = false;
                        this.$emit("cerrarPoput", true);
                    }
                } else
                    this.mostrarNotificacion("Sin OT", "Para generar una bitacora debe crear primero la O.T", 'danger');
            },
            validarFechas() {
                const fechaActual = new Date();
                var res = 'ok';
                if (this.orden.tipoActividad == 5 || this.orden.tipoActividad == 2) {
                    if (this.orden.fechaActividad != null && this.orden.fechaInforme != null) {
                        /*if (this.orden.fechaActividad < fechaActual) {
                            res = "La fecha de la actividad no puede ser menor a la fecha actual!"
                        }
                        if (this.orden.fechaInforme < fechaActual) {
                            res = "La fecha del informe  no puede ser menor a la fecha actual!"
                        }*/
                        if (this.orden.fechaInforme < this.orden.fechaActividad) {
                            res = "La fecha del informe  no puede ser menor a la fecha de la actividad!";
                        }
                    }
                }
                return res;
            },
            consultarEncuenta() {
                axios.post("/consultas/configApp", {atributo: "urlencuesta"})
                    .then(res => {
                        this.urlEncuenta = res.data[0].valor;
                    })
            },
            consultarInforme() {
                axios.post("/consultas/configApp", {atributo: "urlforminforme"})
                    .then(res => {
                        this.urlInforme = res.data[0].valor;
                    })
            },
            actualizarEncuesta() {
                axios.post("/gestor/actualizarEncuesta", {
                    idReserva: this.idReserva,
                    encuesta: this.orden.encuestaOk
                })
                    .then(res => {
                        this.mostrarNotificacion("Actualización exitosa", "Los datos se han actualizado con exito!", "success");
                    })
            },
            actualizarInforme() {
                axios.post("/gestor/actualizarInforme", {
                    idReserva: this.idReserva,
                    informe: this.orden.informeOk
                })
                    .then(res => {
                        this.mostrarNotificacion("Actualización exitosa", "Los datos se han actualizado con exito!", "success");
                    })
            },
            facturarOT() {
                var estadoReserva = 15, estadoOT = 8;
                var mensaje = "La O.T actual se ha facturado con exito!";
                axios.post("/gestor/guardarOrdenRealizada", {
                    "idReserva": this.orden.idReserva,
                    "idOT": this.orden.idOT,
                    "estadoOT": estadoOT,
                    "estadoReserva": estadoReserva
                })
                    .then(res => {
                        this.loading = false;
                        this.mostrarNotificacion("Registro exitoso", mensaje, 'success');
                        location.reload();
                    })
            },
            cancelarOT() {
                this.$swal.fire({
                    title: 'Cancelar la O.T?',
                    text: "Desea cancelar la orden de trabajo actual ?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, cancelar!'
                }).then((result) => {
                    if (result.value) {
                        axios.post("/ordenes/cancelarOT", {idOT: this.orden.idOT, idReserva: this.orden.idReserva})
                            .then(res => {
                                this.mostrarNotificacion("Cancelar O.T", 'La O.T se ha cancelado con exito!', 'success');
                                location.reload();
                            })
                    }
                })
            },
            requiereInforme() {
                return this.orden.tipoActividad == 5 || this.orden.tipoActividad == 2;
            },

            guardarOrdenRealizada(opcion) {
                var estadoReserva = 0, estadoOT = 0;
                var mensaje = "";
                if (opcion == 'otro') {
                    if (!this.requiereInforme()) {
                        estadoOT = 16;
                        estadoReserva = 17;
                        mensaje = "La orden de trabajo a pasado al estado de REALIZADA";
                    } else if (this.requiereInforme() && this.orden.informeOk == 1) {
                        estadoOT = 7;
                        estadoReserva = 14;
                        mensaje = "La orden de trabajo a pasado al estado de REALIZADA PENDIENTE REVISADO";
                    } else if (this.requiereInforme() && this.orden.informeOk == 0) {
                        estadoOT = 6;
                        estadoReserva = 14;
                        mensaje = "La orden de trabajo a pasado al estado de REALIZADA PENDIENTE POR INFORME";
                    } else {
                        estadoReserva = 17;
                        estadoOT = 16;
                        mensaje = "La orden de trabajo a cambiado al estado de REALIZADA";
                    }
                } else {
                    estadoReserva = 17;
                    estadoOT = 16;
                    mensaje = "La orden de trabajo a cambiado al estado de REALIZADA";
                }
                this.guardarOrdenValidada(estadoReserva, estadoOT, mensaje);
            },
            guardarOrdenValidada(estadoReserva, estadoOT, mensaje) {
                this.loading = true;
                this.datosGuardarOrden().then(res => {
                    axios.post("/gestor/guardarOrdenRealizada", {
                        "idReserva": this.orden.idReserva,
                        "idOT": this.orden.idOT,
                        "estadoOT": estadoOT,
                        "estadoReserva": estadoReserva
                    })
                        .then(res => {
                            this.loading = false;
                            this.mostrarNotificacion("Registro exitoso", mensaje, 'success');
                            location.reload();
                        })
                });
            },
            verDocumento(param) {
                var path = param.path, nombre = param.nombre, ext = param.ext.trim();
                this.cargandoGeneral = true;
                this.viewArchivo = '';
                this.abrirPoputVerDoc = true;
                axios.post("/documentos/verDcto", param)
                    .then(res => {
                        var src = res.data + '&hl=en&pid=explorer&efh=false&a=v&chrome=false&embedded=true';
                        if (ext != 'pdf' && ext != 'PDF' && ext != 'png' && ext != 'PNG' && ext != 'jpg' && ext != 'jpeg' && ext != 'JPG') {
                            src = `https://view.officeapps.live.com/op/embed.aspx?src=${res.data}`;
                        }
                        this.cargandoGeneral = false;
                        this.ancho = this.tamanoVentana()[1] - 160;
                        this.viewArchivo = src;
                        this.$nextTick(function () {
                            $(".vue-friendly-iframe").css("height", this.ancho);
                        })
                    })

            },
            tamanoVentana() {
                var tam = [0, 0];
                if (typeof window.innerWidth != 'undefined') {
                    tam = [window.innerWidth, window.innerHeight];
                } else if (typeof document.documentElement != 'undefined'
                    && typeof document.documentElement.clientWidth !=
                    'undefined' && document.documentElement.clientWidth != 0) {
                    tam = [
                        document.documentElement.clientWidth,
                        document.documentElement.clientHeight
                    ];
                } else {
                    tam = [
                        document.getElementsByTagName('body')[0].clientWidth,
                        document.getElementsByTagName('body')[0].clientHeight
                    ];
                }
                return tam;
            },
            loadArchivo() {
                this.cargandoGeneral = false;
            },
            changeVista() {
                this.abrirPoputDoc = false;
                this.consultarDoc();
                this.$emit("actualizarVistaGestor");
            },
            descargarDcto(path, nombre, ext) {
                var a = document.createElement('a');
                a.target = "_blank";
                a.href = "/documentos/descargarDcto?path=" + path + "&nombre=" + nombre + "&ext=" + ext;
                a.click();
            }
        },
        watch: {
            idReserva: {
                immediate: true,
                handler(val, oldVal) {
                    if (this.idReserva != null && this.idReserva != '') {
                        this.obtenerreservaId(val);
                    }
                }
            },
            'orden.encuestaOK': {
                handler: function handler(after, before) {
                    this.actualizarEncuesta();
                },
                deep: true
            },
            'orden.informeOK': {
                handler: function handler(after, before) {
                    this.actualizarInforme();
                },
                deep: true
            },
        }
    }
</script>
<style>
    .iframeArchivo {
        height: 100% !important;
        width: 100% !important;
    }

    .poputSecundario {
        z-index: 53001 !important;
    }
</style>


