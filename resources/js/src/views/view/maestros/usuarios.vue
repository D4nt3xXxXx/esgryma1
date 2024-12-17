<template>
    <vx-card title="Lista de usuarios">
        <vs-button color="warning" type="filled" @click="popupActivo=true;">
            Agregar usuario
        </vs-button>
        <vs-popup class="holamundo" title="Crear usuario" :active.sync="popupActivo">
            <usuario-registrar></usuario-registrar>
        </vs-popup>
        <vs-table :data="this.datosUsuario" pagination :max-items="10" search>
            <template slot="thead">
                <vs-th>ID</vs-th>
                <vs-th>Nombre completo</vs-th>
                <vs-th>Correo</vs-th>
                <vs-th>Tipo usuario</vs-th>
                <vs-th>Rol</vs-th>
                <vs-th>Activo</vs-th>
                <vs-th>Acciones</vs-th>
            </template>

            <template slot-scope="{data}">
                <vs-tr :key="indextr" v-for="(tr, indextr) in data">

                    <vs-td :data="data[indextr].id">
                        {{ data[indextr].id }}
                    </vs-td>

                    <vs-td :data="data[indextr].nombre">
                        {{ data[indextr].nombre }}
                    </vs-td>
                    <vs-td :data="data[indextr].email">
                        <vs-avatar :src="data[indextr].imagen" class="flex-shrink-0 mr-2" size="30px"/>
                        {{ data[indextr].email }}
                    </vs-td>
                    <vs-td :data="data[indextr].tipo_usuario">
                        {{ data[indextr].tipo_usuario }}
                    </vs-td>
                    <vs-td :data="data[indextr].rol">
                        {{ data[indextr].rol }}
                    </vs-td>
                    <vs-td :data="data[indextr].activo">
                        <vs-chip class="ag-grid-cell-chip" :color="data[indextr].activo==1 ? 'primary' : 'danger'">
                            <span>{{ data[indextr].activo==1 ? 'SI' : 'NO' }}</span>
                        </vs-chip>
                    </vs-td>
                    <vs-td>
                        <vs-row vs-type="flex" vs-justify="flex-end">
                            <vs-col vs-sm="6" vs-md="6" vs-lg="6" vs-type="flex" vs-justify="end"
                                    vs-align="end" vs-w="6">
                                <vx-tooltip text="Editar usuario">
                                    <router-link :to="{name:'maestros.usuariosEdit',query:{idUser:data[indextr].id}}">
                                        <span class="material-icons text-info cursor-pointer">remove_red_eye</span>
                                    </router-link>
                                </vx-tooltip>
                                <vx-tooltip text="Eliminar usuario" v-if="data[indextr].rol!='Administrador'">
                                    <span @click="confirmarUsuarioEliminar(data[indextr])"
                                          class="material-icons text-danger cursor-pointer">delete_forever</span>
                                </vx-tooltip>
                            </vs-col>
                        </vs-row>
                    </vs-td>
                </vs-tr>
            </template>
        </vs-table>
    </vx-card>
</template>

<script>
    import usuarioRegistrar from './usuario/registrar'

    export default {
        name: "usuarios",
        data() {
            return {
                datosUsuario: [],
                usuarioEliminar: [],
                popupActivo: false
            }
        },
        components: {
            usuarioRegistrar
        },
        created() {
            this.consultarUsuarios();
        },
        methods: {
            consultarUsuarios() {
                this.cargandoGeneral = true;
                axios.post("/maestros/consultarUsuarios")
                    .then(res => {
                        this.cargandoGeneral = false;
                        this.datosUsuario = res.data;
                    })
            },
            confirmarUsuarioEliminar(usuario) {
                this.usuarioEliminar = usuario;
                this.$vs.dialog({
                    type: 'confirm',
                    color: 'danger',
                    title: `Eliminar usuario`,
                    text: `Desea eliminar el usuario  "${usuario.nombre}"`,
                    accept: this.eliminarusuario,
                    acceptText: "Eliminar"
                })
            },
            eliminarusuario() {
                axios.post("/maestros/eliminarUsuario", this.usuarioEliminar)
                    .then(res => {
                        if (res.data == 'ok')
                            this.mostrarNotificacion("Eliminación usuario", 'El usuario ' + this.usuarioEliminar.nombre + ', se ha elimnado con exito', "success");
                        else {
                            this.$swal("Eliminar usuario", "Lo sentimos el usuario no se pudo eliminar:" + res.data, 'error');
                        }
                        this.consultarUsuarios();
                    })
            }
        }
    }
</script>

<style scoped>

</style>