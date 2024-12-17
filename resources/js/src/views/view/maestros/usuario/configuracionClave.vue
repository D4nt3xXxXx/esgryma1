<template>
    <vx-card no-shadow>
        <ValidationObserver ref="form">
            <form @submit.prevent="actualizarClave">
                <validation-provider name="CONTRASEÑA" rules="required|confirmed:confirmacion|min:8"
                                     v-slot="{ errors }">
                    <vs-input ref="password" type="password" class="w-full mb-base" icon-after="true"
                              label-placeholder="Contraseña" icon="vpn_key" v-model="nueva_clave" name="clave"/>
                    <span class="text-danger">{{ errors[0] }}</span>
                </validation-provider>
                <validation-provider name="REPETIR CONTRASEÑA" rules="required|min:8" vid="confirmacion"
                                     v-slot="{ errors }">
                    <vs-input type="password" class="w-full mb-base" icon-after="true"
                              label-placeholder="Repetir contraseña" icon="vpn_key" v-model="confirmar_clave"
                              name="password"/>
                    <span class="text-danger">{{ errors[0] }}</span>
                </validation-provider>
                <!-- Save & Reset Button -->
                <div class="flex flex-wrap items-center justify-end">
                    <boton texto_cargando="Guardando nueva clave..." class="ml-auto mt-2"
                           texto="Actualizar contraseña" :cargando="activeLoading" type="submit"></boton>
                </div>
            </form>
        </ValidationObserver>
    </vx-card>
</template>

<script>
    export default {
        props: {
            usuario: {
                required: true,
                type: [Object, Array]
            }
        },
        data() {
            return {
                nueva_clave: "",
                confirmar_clave: "",
            }
        },
        computed: {},
        methods: {
            actualizarClave() {
                this.activeLoading = true;
                axios.post("/maestros/actualizarClave", {id: this.usuario.id, clave: this.nueva_clave})
                    .then(res => {
                        this.activeLoading = false;
                        this.mostrarNotificacion("Actualización de contraseña", "La contraseña se ha actualizado con exito!", "success")
                        this.nueva_clave = this.confirmar_clave = '';
                    })
            }
        }
    }
</script>
