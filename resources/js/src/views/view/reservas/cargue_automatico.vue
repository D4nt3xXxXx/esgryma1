<template>
    <div id="page-user-list">
        <vs-row vs-justify="center">
            <vs-col type="flex" vs-justify="center" vs-align="center" vs-w="6">
                <vx-card ref="filterCard" title="Cargue automático de reservas"
                         class="user-list-filters col-xl-8 col-11 d-flex justify-content-center">
                    <ValidationObserver v-slot="{ handleSubmit }">
                        <form @submit.prevent="handleSubmit(subirReservas)">
                            <vs-row vs-type="flex" vs-justify="flex-end">

                                <vs-col :key="index" v-for="col,index in 1" vs-type="flex" vs-justify="center"
                                        vs-align="center" vs-w="4">
                                    <a href="/reservas/descargarArchivoReserva" target="_blank" class="">
                                        <vs-button color="dark" type="line" icon-pack="material-icons" icon="get_app"
                                                   icon-after>Descargar plantilla
                                        </vs-button>
                                    </a>
                                </vs-col>
                            </vs-row>
                            <div class="vx-row">
                                <div class="vx-col md:w-12/12 sm:w-12/12 w-full">

                                    <validation-provider name="Cliente" rules="required" v-slot="{ errors }">
                                        <strong>Cliente</strong>
                                        <Select2 class="vx-col md:w-12/12 sm:w-12/12 w-full" v-model="cliente"
                                                 :options="listaCliente"/>
                                        <span class="text-danger">{{ errors[0] }}</span>
                                    </validation-provider>
                                </div>
                                <div class="vx-col md:w-12/12 sm:w-12/12 w-full form-group p-6">
                                    <strong>Vencimiento ?</strong>
                                    <vs-switch color="dark" icon-pack="feather" vs-icon-on="icon-check-circle"
                                               vs-icon-off="icon-slash" v-model="fecha_manual">
                                        <span slot="on">SI</span>
                                        <span slot="off">NO</span>
                                    </vs-switch>
                                    <div class="vx-row" v-if="!fecha_manual">
                                        <div class="vx-col md:w-6/12 lg:w-6/12 sm:w-12/12">
                                            <strong>Fecha de inicio</strong>
                                            <validation-provider name="Fecha Inicio" rules="required"
                                                                 v-slot="{ errors }">
                                                <datetime type="date" input-class="vs-inputx vs-input--input normal"
                                                          value-zone="America/Bogota"
                                                          zone="America/Bogota" v-model="fecha_inicio"
                                                          format="yyyy-MM-dd"
                                                          auto></datetime>
                                                <span class="text-danger">{{ errors[0] }}</span>
                                            </validation-provider>
                                        </div>
                                        <div class="vx-col md:w-6/12 lg:w-6/12 sm:w-12/12">
                                            <strong>Fecha fin</strong>
                                            <validation-provider name="Fecha Inicio" rules="required"
                                                                 v-slot="{ errors }">
                                                <datetime type="date" input-class="vs-inputx vs-input--input normal"
                                                          value-zone="America/Bogota"
                                                          zone="America/Bogota" v-model="fecha_fin"
                                                          format="yyyy-MM-dd"
                                                          auto></datetime>
                                                <span class="text-danger">{{ errors[0] }}</span>
                                            </validation-provider>
                                        </div>
                                    </div>
                                </div>
                                <div class="vx-col md:w-12/12 sm:w-12/12 w-full text-center">
                                    <div class="example-drag">
                                        <div class="upload">
                                            <ul v-if="files.length">
                                                <li v-for="(file, index) in files" :key="file.id">
                                                    <span>{{file.name}}</span> -
                                                    <span>{{file.size | formatSize}}</span> -
                                                    <span v-if="file.error">{{file.error}}</span>
                                                    <span v-else-if="file.success">success</span>
                                                    <span v-else-if="file.active">active</span>
                                                    <span v-else></span>
                                                </li>
                                            </ul>
                                            <ul v-else>
                                                <div class="text-center p-5">
                                                    <h4>Arrastre el archivo a esta zona<br/> o</h4>
                                                </div>
                                            </ul>

                                            <div v-show="$refs.upload && $refs.upload.dropActive" class="drop-active">
                                                <h3>Drop files to upload</h3>
                                            </div>

                                            <div class="example-btn">
                                                <validation-provider name="Archivo Reservas" rules="required"
                                                                     v-slot="{ errors }">
                                                    <file-upload
                                                            class="vs-component vs-button vs-button-success vs-button-filled"
                                                            :multiple="false"
                                                            :drop="true"
                                                            :drop-directory="false"
                                                            v-model="files"
                                                            extensions="xls,xlsx"
                                                            :extensions="['xls','xlsx']"
                                                            @input-filter="inputFilter"
                                                            ref="upload">
                                                        <i class="fa fa-plus"></i>
                                                        Seleccione el archivo de reservas
                                                    </file-upload>
                                                    <div class="text-danger">{{ errors[0] }}</div>
                                                </validation-provider>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="vx-col md:w-12/12 sm:w-12/12 w-full text-center form-group p-4">
                                    <!--<button type="submit" v-if="!$refs.upload || !$refs.upload.active"
                                            class="vs-component vs-button vs-button-primary vs-button-filled"
                                            v-show="!$refs.upload || !$refs.upload.active">
                                        Subir reservas
                                    </button>-->
                                    <boton texto_cargando="Guardando..." type="submit" @click="subirReservas"
                                           texto="Guardar reservas" :cargando="loading"></boton>
                                </div>
                                <div v-if="mensaje.mensaje!=''" class="vx-col md:w-12/12 sm:w-12/12 w-full text-center">
                                    <!--<h5>{{mensaje.mensaje}}</h5>-->
                                    <vs-alert :active="mensaje.mensaje" color="danger" icon-pack="feather"
                                              icon="icon-info">
                                        <span>{{mensaje.mensaje}}</span>
                                    </vs-alert>
                                    <vs-list>
                                        <vs-list-item v-for="item in mensaje.datos" :key="item.nroOrden"
                                                      :title="item.nroOrden || item"></vs-list-item>
                                    </vs-list>
                                </div>
                            </div>
                        </form>
                    </ValidationObserver>
                </vx-card>
            </vs-col>
        </vs-row>
    </div>
</template>

<script>
    import VueUploadComponent from 'vue-upload-component'

    export default {
        name: "cargue_automatico",
        components: {FileUpload: VueUploadComponent},
        data() {
            return {
                cliente: null,
                listaCliente: [],
                files: [],
                mensaje: {
                    mensaje: '',
                    datos: []
                },
                fecha_manual: true,
                fecha_inicio: '',
                fecha_fin: ''
            }
        },
        created() {
            this.listarClientes();
        },
        methods: {
            listarClientes() {
                axios.post("/reservas/listaClientes")
                    .then(res => {
                        this.listaCliente = res.data;
                    });
            },
            subirReservas() {
                this.loading = true;
                const config = {
                    headers: {'content-type': 'multipart/form-data'}
                }

                let formData = new FormData();
                formData.append('archivo_excel', this.files[0].file);
                formData.append('cliente', this.cliente);
                formData.append('fechaManual', this.fecha_manual ? 1 : 0);
                formData.append('fechaInicio', this.fecha_inicio);
                formData.append('fechaFinal', this.fecha_fin);
                this.$refs.upload.active = false;
                axios.post("/import-os", formData, config)
                    .then(res => {
                        if (res.data.mensaje == 'ok') {
                            this.$swal("Las reservas se han subido con exito.");
                            this.reset();
                        }
                        else {
                            this.mensaje.mensaje = res.data.mensaje;
                            this.mensaje.datos = res.data.data;
                        }
                        this.loading = false;
                    })

            },
            inputFilter(newFile, oldFile, prevent) {
                if (newFile && !oldFile) {
                    // Add file

                    // Filter non-image file
                    // Will not be added to files
                    if (!/\.(xls|xlsx)$/i.test(newFile.name)) {
                        this.mensaje.mensaje = "extensión incorrecta, vuelva a intentar de nuevo.";
                        return prevent()
                    }
                    this.mensaje.mensaje = "";
                    // Create the 'blob' field for thumbnail preview
                    newFile.blob = ''
                    let URL = window.URL || window.webkitURL
                    if (URL && URL.createObjectURL) {
                        newFile.blob = URL.createObjectURL(newFile.file)
                    }
                }

                if (newFile && oldFile) {
                    // Update file

                    // Increase the version number
                    if (!newFile.version) {
                        newFile.version = 0
                    }
                    newFile.version++
                }

                if (!newFile && oldFile) {
                    // Remove file

                    // Refused to remove the file
                    // return prevent()
                }
            },
            descargarArchivoBase() {
                axios({
                    method: 'get',
                    url: "/ARCHIVO_BASE.xls",
                    responseType: 'arraybuffer'
                })
                    .then(response => {

                        this.forceFileDownload(response)

                    })
                    .catch(() => console.log('error occured'))
            },
            forceFileDownload(response) {
                const url = window.URL.createObjectURL(new Blob([response.data]))
                const link = document.createElement('a')
                link.href = url
                link.setAttribute('download', 'ARCHIVO_BASE.xls') //or any other extension
                document.body.appendChild(link)
                link.click()
            },
            reset() {
                location.reload();
            }
        }
    }
</script>

<style>
    .example-drag label.btn {
        margin-bottom: 0;
        margin-right: 1rem;
    }

    .example-drag .drop-active {
        top: 0;
        bottom: 0;
        right: 0;
        left: 0;
        position: fixed;
        z-index: 9999;
        opacity: .6;
        text-align: center;
        background: #000;
    }

    .example-drag .drop-active h3 {
        margin: -.5em 0 0;
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        -webkit-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        transform: translateY(-50%);
        font-size: 40px;
        color: #fff;
        padding: 0;
    }
</style>
