<!-- =========================================================================================
  File Name: UserEditTabInformation.vue
  Description: User Edit Information Tab content
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->

<template>
    <div id="user-edit-tab-info">

        <!-- Avatar Row -->
        <div class="vx-row">
            <div class="vx-col w-full">
                <div class="flex items-start flex-col sm:flex-row">
                    <vs-avatar :src="data.imagen" size="70px" class="mr-4 mb-4"/>
                    <!-- <vs-avatar :src="data.avatar" size="80px" class="mr-4" /> -->
                    <div>
                        <p class="text-lg font-medium mb-2 mt-4 sm:mt-0">{{ data.nombre }}</p>
                        <input type="file" id="imagen" class="hidden" ref="update_avatar_input" @change="update_avatar"
                               accept="image/*">
                        <boton texto_cargando="Guardando imagen..." class="mr-4 mb-4"
                               texto="Actualizar foto" :cargando="activeLoading" @click.native="buscarImagen"></boton>

                        <boton v-if="data_local.imagen!='' && data_local.imagen!=null"
                               texto_cargando="Eliminando imagen..."
                               texto="" class="mr-4 mb-4 vs-button vs-button-danger vs-button-border"
                               :cargando="activeLoading" @click.native="preguntarEliminarFoto">
                            Eliminar foto
                        </boton>
                    </div>
                </div>
            </div>
        </div>
        <ValidationObserver ref="form">
            <form @submit.prevent="guardarCambiosUsuario">
                <!-- Content Row -->
                <div class="vx-row">
                    <div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 w-full">
                        <validation-provider name="NOMBRE" rules="required"
                                             v-slot="{ errors }">
                            <vs-input class="w-full mt-4" label="Nombre completo" v-model="data_local.nombre"
                                      name="nombre"/>
                            <span class="text-danger">{{ errors[0] }}</span>
                        </validation-provider>
                    </div>
                    <div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 w-full">
                        <validation-provider name="EMAIL" rules="required|email"
                                             v-slot="{ errors }">
                            <vs-input class="w-full mt-4" label="Correo" v-model="data_local.email" type="email"
                                      name="email"/>
                            <span class="text-danger">{{ errors[0] }}</span>
                        </validation-provider>
                    </div>

                    <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full">
                        <validation-provider name="TIPO USUARIO" rules="required"
                                             v-slot="{ errors }">
                            <vs-input class="w-full mt-4" label="Tipo usuario" v-model="data_local.tipo_usuario"
                                      name="tipo_usuario"/>
                            <span class="text-danger">{{ errors[0] }}</span>
                        </validation-provider>
                    </div>
                    <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full">
                        <vs-input class="w-full mt-4" label="Empresa" v-model="empresa"
                                  name="empresa"/>
                    </div>
                    <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full mt-4">
                        <label>Activo</label>
                        <validation-provider name="ACTIVO" rules="required|email"
                                             v-slot="{ errors }">
                            <vs-switch v-model="data_local.activo">
                                <span slot="on">SI</span>
                                <span slot="off">NO</span>
                            </vs-switch>
                            <span class="text-danger">{{ errors[0] }}</span>
                        </validation-provider>
                    </div>
                </div>
                <!-- Permissions -->
                <vx-card class="mt-base" no-shadow card-border>

                    <div class="vx-row">
                        <div class="vx-col w-full">
                            <div class="flex items-end px-3">
                                <feather-icon svgClasses="w-6 h-6" icon="LockIcon" class="mr-2"/>
                                <span class="font-medium text-lg leading-none">Permisos</span>
                            </div>
                            <vs-divider/>
                        </div>
                    </div>

                    <div class="block overflow-x-auto">
                        <table class="w-full">
                            <tr>
                                <th class="font-semibold text-base text-left px-3 py-2"
                                    v-for="heading in ['Permiso', 'Agregar']" :key="heading">{{
                                    heading
                                    }}
                                </th>
                            </tr>

                            <tr v-for="(item, index) in permisos" :key="name">
                                <td class="px-3 py-2">{{ item.name }}</td>
                                <td class="px-3 py-2" :key="index">
                                    <vs-checkbox v-model="permisosAsociados" :vs-value="item.id"/>
                                </td>
                            </tr>
                        </table>
                    </div>

                </vx-card>
                <!-- Permissions -->
                <vx-card class="mt-base" no-shadow card-border>

                    <div class="vx-row">
                        <div class="vx-col w-full">
                            <div class="flex items-end px-3">
                                <feather-icon svgClasses="w-6 h-6" icon="LockIcon" class="mr-2"/>
                                <span class="font-medium text-lg leading-none">Roles</span>
                            </div>
                            <vs-divider/>
                        </div>
                    </div>

                    <div class="block overflow-x-auto">
                        <table class="w-full">
                            <tr>
                                <th class="font-semibold text-base text-left px-3 py-2"
                                    v-for="heading in ['Rol', 'Agregar']" :key="heading">{{
                                    heading
                                    }}
                                </th>
                            </tr>

                            <tr v-for="(item, index) in roles" :key="name">
                                <td class="px-3 py-2">{{ item.name }}</td>
                                <td class="px-3 py-2" :key="index">
                                    <vs-checkbox v-model="rolesAsociados" :vs-value="item.id"
                                                 @input="validarRolesCheck"/>
                                </td>
                            </tr>
                        </table>
                    </div>

                </vx-card>
                <div class="vx-row">
                    <div class="vx-col w-full">
                        <div class="mt-8 flex flex-wrap items-center justify-end">
                            <boton texto_cargando="Guardando datos..." type="submit"
                                   texto="Guardar datos" :cargando="activeLoading"></boton>
                        </div>
                    </div>
                </div>
            </form>
        </ValidationObserver>
    </div>
</template>

<script>
    import vSelect from 'vue-select'

    export default {
        components: {
            vSelect
        },
        props: {
            data: {
                type: Object,
                required: true,
            },
        },
        data() {
            return {
                data_local: JSON.parse(JSON.stringify(this.data)),
                statusOptions: [
                    {label: "Activo", value: 1},
                    {label: "Inactivo", value: 0},
                ],
                empresa: 'ESGRYMA CONSULTING GROUP',
                imagen: null,
                permisos: [],
                roles: [],
                permisosAsociados: [],
                rolesAsociados: [],
                tempRolesAsignados: [],
                rolesAgregar: [],
                rolesEliminar: []
            }
        },
        computed: {},
        created() {
            this.consultarPermisos();
            this.consultarRoles();
            this.permisosUsuarios();
            this.rolesUsuarios();
        },
        methods: {
            save_changes() {

            },
            reset_data() {
                this.data_local = JSON.parse(JSON.stringify(this.data))
            },
            update_avatar(e) {
                this.imagen = e.target.files[0];
                this.$vs.dialog({
                    type: 'confirm',
                    color: 'warning',
                    title: `Actualizar imagen`,
                    text: `Desea actualizar la imagen de perfil ?`,
                    accept: this.actualizarImagen,
                    acceptText: "Actualizar"
                });
            },
            actualizarImagen() {
                const config = {
                    headers: {'content-type': 'multipart/form-data'}
                };

                let formData = new FormData();
                formData.append('imagen', this.imagen);
                formData.append('id', this.data_local.id);
                axios.post('/maestros/actualizarImagen', formData, config)
                    .then(res => {
                        location.reload();
                    })
            },
            guardarCambiosUsuario() {
                const datosUsuario = {
                    id: this.data_local.id,
                    name: this.data_local.nombre,
                    email: this.data_local.email,
                    activo: this.data_local.activo,
                    tipo_usuario: this.data_local.tipo_usuario
                };
                this.activeLoading = true;
                axios.post("/maestros/guardarDatosUsuario", {
                    "datoUsuario": datosUsuario,
                    "rolesAgregar": this.rolesAgregar,
                    "rolesEliminar": this.rolesEliminar
                })
                    .then(res => {
                        this.mostrarNotificacion("Actualizar usuario", "Datos actualizados con exito!", "success");
                        this.activeLoading = false;
                        location.reload();
                    })
            },
            buscarImagen() {
                $('#imagen').click();
            },
            preguntarEliminarFoto() {
                this.$vs.dialog({
                    type: 'confirm',
                    color: 'warning',
                    title: `Eliminar foto de perfil`,
                    text: `Desea eliminar la imagen de perfil ?`,
                    accept: this.guardarEliminarImagen,
                    acceptText: "Eliminar"
                });
            },
            guardarEliminarImagen() {
                this.activeLoading = true;
                axios.post("/maestros/eliminarFoto", {"id": this.data_local.id})
                    .then(res => {
                        this.activeLoading = false;
                        location.reload();
                    })
            },
            consultarPermisos() {
                axios.post("/maestros/listaPermisos")
                    .then(res => {
                        this.permisos = res.data;
                    })
            },
            consultarRoles() {
                axios.post("/maestros/listaRoles")
                    .then(res => {
                        this.roles = res.data;
                    })
            },
            permisosUsuarios() {
                axios.post("/maestros/permisosUsuario", {id: this.data_local.id})
                    .then(res => {
                        var vm = this;
                        $.each(res.data, function (index, value) {
                            vm.permisosAsociados.push(value.id);
                        })
                    })
            },
            rolesUsuarios() {
                axios.post("/maestros/rolesUsuario", {id: this.data_local.id})
                    .then(res => {
                        var vm = this;
                        $.each(res.data, function (index, value) {
                            vm.rolesAsociados.push(value.id);
                            vm.tempRolesAsignados.push(value.id);
                        })
                    })
            },
            validarRolesCheck() {
                var vm = this;
                this.rolesAgregar = [];
                this.rolesEliminar = [];
                $.each(this.rolesAsociados, function (index, value) {
                    var existe = vm.tempRolesAsignados.findIndex(x => x == value);
                    if (existe == -1)
                        vm.rolesAgregar.push(value);
                })
                $.each(this.tempRolesAsignados, function (index, value) {
                    var existe = vm.rolesAsociados.findIndex(x => x == value);
                    if (existe == -1)
                        vm.rolesEliminar.push(value);
                })
            }
        },
    }
</script>
