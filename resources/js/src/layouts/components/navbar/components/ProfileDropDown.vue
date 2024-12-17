<template>
    <div class="the-navbar__user-meta flex items-center" v-if="activeUserInfo.name">

        <div class="text-right leading-tight hidden sm:block">
            <p class="font-semibold">{{ activeUserInfo.name }}</p>
            <vs-button style="padding: 0px" @click="popupActive=true" color="primary" type="border">
                {{activeUserInfo.roles.length>0 ? activeUserInfo.roles[0].name : 'Sin rol'}}
            </vs-button>
            <vs-popup class="holamundo" title="Mis Roles" :active.sync="popupActive">
                <ul class="centerx" id="ul_roles">
                    <li v-for="item in $store.state.esgryma.roles">
                        <vs-radio v-model="rolSeleccionado" :vs-value="item.slug">{{item.name}}</vs-radio>
                    </li>
                </ul>
            </vs-popup>

        </div>
        <vs-dropdown vs-custom-content vs-trigger-click class="cursor-pointer">
            <div class="con-img ml-3">
                <i v-if="activeUserInfo.imagen==null || activeUserInfo.imagen==''"
                   class="material-icons rounded-full shadow-md cursor-pointer block" key="onlineImg"
                   style="width: 40px;height: 40px;font-size: 40px">account_circle</i>
                <img v-if="activeUserInfo.imagen" key="onlineImg" :src="activeUserInfo.imagen" alt="user-img"
                     width="40" height="40" class="rounded-full shadow-md cursor-pointer block"/>
            </div>
            <vs-dropdown-menu class="vx-navbar-dropdown">
                <ul style="min-width: 9rem">
                    <!--<li
                      class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white"
                      @click="$router.push('/pages/profile').catch(() => {})">
                      <feather-icon icon="UserIcon" svgClasses="w-4 h-4" />
                      <span class="ml-2">Profile</span>
                    </li>

                    <li
                      class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white"
                      @click="$router.push('/apps/email').catch(() => {})">
                      <feather-icon icon="MailIcon" svgClasses="w-4 h-4" />
                      <span class="ml-2">Inbox</span>
                    </li>

                    <li
                      class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white"
                      @click="$router.push('/apps/todo').catch(() => {})">
                      <feather-icon icon="CheckSquareIcon" svgClasses="w-4 h-4" />
                      <span class="ml-2">Tasks</span>
                    </li>

                    <li
                      class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white"
                      @click="$router.push('/apps/chat').catch(() => {})">
                      <feather-icon icon="MessageSquareIcon" svgClasses="w-4 h-4" />
                      <span class="ml-2">Chat</span>
                    </li>

                    <li
                      class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white"
                      @click="$router.push('/apps/eCommerce/wish-list').catch(() => {})">
                      <feather-icon icon="HeartIcon" svgClasses="w-4 h-4" />
                      <span class="ml-2">Wish List</span>
                    </li>-->

                    <vs-divider class="m-1"/>

                    <!--<li
                        class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white"
                        @click="logout">
                        <feather-icon icon="LogOutIcon" svgClasses="w-4 h-4"/>
                        <span class="ml-2">Logout</span>
                    </li>-->
                    <li
                        class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white"
                        @click="$router.push('/app/permitirNotificaciones').catch(() => {})">
                        <!--<feather-icon icon="HeartIcon" svgClasses="w-4 h-4" />-->
                        <span class="ml-2">Permitir notificaciones</span>
                    </li>
                    <li :href="urllogout" id="cerrarsesion"
                        class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white" data-toggle="tooltip"
                        title="Cerrar sessión"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <feather-icon icon="LogOutIcon" svgClasses="w-4 h-4"/>
                        <span class="ml-2">Salir</span>
                        <form id="logout-form" :action="urllogout" method="POST" style="display: none;">
                            <input type="hidden" name="_token" id="_token"
                                   :value="token">
                        </form>
                    </li>
                </ul>
            </vs-dropdown-menu>
        </vs-dropdown>
    </div>
</template>

<script>
    import firebase from 'firebase/app'
    import 'firebase/auth'

    export default {
        data() {
            return {
                token: '',
                urllogout: '',
                popupActive: false
            }
        },
        mounted() {
            this.token = $("meta[name='csrf-token']").attr("content");
            this.urllogout = window.location.protocol + "//" + window.location.host + "/logout";
        },
        computed: {
            activeUserInfo() {
                return this.$store.state.esgryma
            }
        },
        methods: {
            logout() {

                // if user is logged in via auth0
                if (this.$auth.profile) this.$auth.logOut();

                // if user is logged in via firebase
                const firebaseCurrentUser = firebase.auth().currentUser

                if (firebaseCurrentUser) {
                    firebase.auth().signOut().then(() => {
                        this.$router.push('/pages/login').catch(() => {
                        })
                    })
                }
                // If JWT login
                if (localStorage.getItem("accessToken")) {
                    localStorage.removeItem("accessToken")
                    this.$router.push('/pages/login').catch(() => {
                    })
                }

                // Change role on logout. Same value as initialRole of acj.js
                this.$acl.change('admin')
                localStorage.removeItem('userInfo')

                // This is just for demo Purpose. If user clicks on logout -> redirect
                this.$router.push('/pages/login').catch(() => {
                })
            },
            openAlert(color) {
                const radios = $("#ul_roles").clone();
                this.$vs.dialog({
                    color: color,
                    title: `Roles del usuario`,
                    text: '<div id="copied"></div>',
                    accept: this.acceptAlert
                })
                $("#copied").html(radios);
            },
            prueba() {
                alert(2);
            }
        }
    }
</script>
