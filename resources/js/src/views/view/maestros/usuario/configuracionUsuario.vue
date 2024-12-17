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

        <vs-alert color="danger" title="Usuario no existe" :active.sync="user_not_found">
            <span>Usuario con id: {{ $route.query.idUser }} no existe en nuestros registros. </span>
            <span>
        <span>Ver todos los usuarios </span><router-link :to="{name:'maestros.usuarios'}"
                                                         class="text-inherit underline">Todos los usuarios</router-link>
      </span>
        </vs-alert>

        <vx-card v-if="user_data">

            <div slot="no-body" class="tabs-container px-6 pt-6">

                <vs-tabs v-model="activeTab" class="tab-action-btn-fill-conatiner">
                    <vs-tab label="Información general" icon-pack="feather" icon="icon-user">
                        <div class="tab-text">
                            <editar-informacion-general class="mt-4" :data="user_data"/>
                        </div>
                    </vs-tab>
                    <vs-tab label="Contraseña" icon-pack="feather" icon="icon-info">
                        <div class="tab-text">
                            <configuracion-clave :usuario="user_data"></configuracion-clave>
                        </div>
                    </vs-tab>
                </vs-tabs>

            </div>

        </vx-card>
    </div>
</template>

<script>
    import editarInformacionGeneral from "./configuracionGeneral"

    // Store Module
    import moduleUserManagement from '@/store/user-management/moduleUserManagement.js'
    import ConfiguracionClave from "./configuracionClave";

    export default {
        components: {
            ConfiguracionClave,
            editarInformacionGeneral
        },
        data() {
            return {
                user_data: null,
                user_not_found: false,
                activeTab: 0,
            }
        },
        watch: {
            activeTab() {
                this.fetch_user_data(this.$route.query.idUser)
            }
        },
        methods: {
            fetch_user_data(userId) {
                this.cargandoGeneral=true;
                axios.post("/maestros/informacionUsuario", {"id": userId})
                    .then(res => {
                        this.user_data = res.data[0][0];
                        this.cargandoGeneral=false;
                    })
            }
        },
        created() {
            // Register Module UserManagement Module
            if (!moduleUserManagement.isRegistered) {
                this.$store.registerModule('userManagement', moduleUserManagement)
                moduleUserManagement.isRegistered = true
            }
            this.fetch_user_data(this.$route.query.idUser)
        }
    }

</script>
