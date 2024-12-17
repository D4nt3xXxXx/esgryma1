<template>
    <ValidationObserver v-slot="{ handleSubmit }">
        <form @submit.prevent="handleSubmit(guardarUsuario)">
            <!-- Content Row -->
            <div class="vx-row">
                <div class="vx-col sm:w-12/12 md:w-12/12 lg:w-12/12 w-full form-group mb-2">
                    <validation-provider name="NOMBRE" rules="required"
                                         v-slot="{ errors }">
                        <vs-input class="w-full mt-4" label="Nombre completo" v-model="data_local.name"
                                  name="nombre"/>
                        <span class="text-danger">{{ errors[0] }}</span>
                    </validation-provider>
                </div>
                <div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 w-full form-group mb-2">
                    <validation-provider name="EMAIL" rules="required|email"
                                         v-slot="{ errors }">
                        <vs-input class="w-full" label="Correo" v-model="data_local.email" type="email"
                                  name="email"/>
                        <span class="text-danger">{{ errors[0] }}</span>
                    </validation-provider>
                </div>

                <div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 w-full form-group mb-2">
                    <validation-provider name="TIPO USUARIO" rules="required"
                                         v-slot="{ errors }">
                        <vs-select autocomplete label="Tipo"
                                   v-model="data_local.tipo_usuario" style="width:100%">
                            <vs-select-item :key="''" :value="''" :text="'---seleccione---'"/>
                            <vs-select-item key="ESGRYMA" value="ESGRYMA" text="ESGRYMA"/>
                            <vs-select-item key="CONTRATISTA" value="CONTRATISTA" text="CONTRATISTA"/>
                        </vs-select>
                        <span class="text-danger">{{ errors[0] }}</span>
                    </validation-provider>
                </div>
            </div>
            <div class="vx-row">
                <div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 w-full form-group mb-2">
                    <validation-provider name="CONTRASEÑA" rules="required|confirmed:confirmacion|min:8"
                                         v-slot="{ errors }">
                        <vs-input ref="password" type="password" class="w-full mb-base" icon-after="true"
                                  label-placeholder="Contraseña" icon="vpn_key" v-model="data_local.password"
                                  name="clave"/>
                        <span class="text-danger">{{ errors[0] }}</span>
                    </validation-provider>
                </div>
                <div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 w-full form-group mb-2">
                    <validation-provider name="REPETIR CONTRASEÑA" rules="required|min:8" vid="confirmacion"
                                         v-slot="{ errors }">
                        <vs-input type="password" class="w-full mb-base" icon-after="true"
                                  label-placeholder="Repetir contraseña" icon="vpn_key" v-model="confirmar_clave"
                                  name="password"/>
                        <span class="text-danger">{{ errors[0] }}</span>
                    </validation-provider>
                </div>
            </div>
            <div class="vx-row">
                <div class="vx-col w-full text-center">
                    <boton texto_cargando="Guardando usuario..." type="submit"
                           texto="Guardar usuario" :cargando="activeLoading"></boton>
                </div>
            </div>
        </form>
    </ValidationObserver>
</template>

<script>
    export default {
        name: "registrar",
        data() {
            return {
                data_local: {
                    name: '',
                    email: '',
                    password: '',
                    activo: 1,
                    tipo_usuario: ''
                },
                confirmar_clave: ''
            }
        },
        methods: {
            guardarUsuario() {
                axios.post("/maestros/guardarUsuario", this.data_local)
                    .then(res => {
                        if (res.data == 'ok') {
                            this.mostrarNotificacion("Guardar usuario", "Usuario registrado con exito!", "success");
                            this.reset();
                            this.$parent.$parent.$parent.consultarUsuarios();
                            this.$parent.$parent.$parent.popupActivo = false;
                        } else {
                            this.mostrarNotificacion("Usuario existe", res.data, "warning");
                        }
                    });
            },
            reset() {
                this.data_local = {
                    name: '',
                    email: '',
                    password: '',
                    activo: 1,
                    tipo_usuario: ''
                };
                this.confirmar_clave = '';
            }
        }
    }
</script>

<style scoped>

</style>