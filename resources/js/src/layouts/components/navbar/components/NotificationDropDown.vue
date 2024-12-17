<template>
    <!-- NOTIFICATIONS -->
    <vs-dropdown vs-custom-content vs-trigger-click class="cursor-pointer">
        <feather-icon icon="BellIcon" class="cursor-pointer mt-1 sm:mr-6 mr-2" :badge="total"/>

        <vs-dropdown-menu class="notification-dropdown dropdown-custom vx-navbar-dropdown">

            <div class="notification-top text-center p-5 bg-primary text-white">
                <h3 class="text-white">{{ total }} Nuevas</h3>
                <p class="opacity-75">SmartProg notificaciones</p>
            </div>

            <VuePerfectScrollbar ref="mainSidebarPs" class="scroll-area--nofications-dropdown p-0 mb-10"
                                 :settings="settings" :key="$vs.rtl">
                <ul class="bordered-items">
                    <li v-for="item in notifications" :key="item.id"
                        class="flex justify-between px-4 py-4 notification cursor-pointer">
                        <div class="flex items-start">
                            <i class="material-icons stroke-current mr-1 h-6 w-6">message</i>
                            <div class="mx-2">
                                <span class="font-medium block notification-title" :class="[`text-${item.category}`]">{{ item.titulo}}</span>
                                <small>{{ item.mensaje }}</small>
                            </div>
                        </div>
                        <small class="mt-1 whitespace-no-wrap">
                            <timeago :datetime="item.creada" :autoUpdate="1" :includeSeconds="true"
                                     locale="es"></timeago>
                        </small>
                    </li>
                </ul>
            </VuePerfectScrollbar>

            <div class="
        checkout-footer
        fixed
        bottom-0
        rounded-b-lg
        text-primary
        w-full
        p-2
        font-semibold
        text-center
        border
        border-b-0
        border-l-0
        border-r-0
        border-solid
        d-theme-border-grey-light
        cursor-pointer">
                <span>View All Notifications</span>
            </div>
        </vs-dropdown-menu>
    </vs-dropdown>
</template>

<script>
    import VuePerfectScrollbar from 'vue-perfect-scrollbar'
    import {DateTime} from 'luxon'

    export default {
        components: {
            VuePerfectScrollbar
        },
        data() {
            return {
                total: 0,
                notifications: [],
                settings: {
                    maxScrollbarLength: 60,
                    wheelSpeed: .60,
                },
                fecha: DateTime.local()
            }
        },
        created() {
            if (window.Echo) {
                //this.escucharnotificaciones();
                this.listen();
            }
            this.listarNotificaciones();
        },
        methods: {
            elapsedTime(startTime) {
                let x = new Date(startTime)
                let now = new Date()
                var timeDiff = now - x
                timeDiff /= 1000

                var seconds = Math.round(timeDiff)
                timeDiff = Math.floor(timeDiff / 60)

                var minutes = Math.round(timeDiff % 60)
                timeDiff = Math.floor(timeDiff / 60)

                var hours = Math.round(timeDiff % 24)
                timeDiff = Math.floor(timeDiff / 24)

                var days = Math.round(timeDiff % 365)
                timeDiff = Math.floor(timeDiff / 365)

                var years = timeDiff

                if (years > 0) {
                    return years + (years > 1 ? ' Years ' : ' Year ') + 'ago'
                } else if (days > 0) {
                    return days + (days > 1 ? ' Days ' : ' Day ') + 'ago'
                } else if (hours > 0) {
                    return hours + (hours > 1 ? ' Hrs ' : ' Hour ') + 'ago'
                } else if (minutes > 0) {
                    return minutes + (minutes > 1 ? ' Mins ' : ' Min ') + 'ago'
                } else if (seconds > 0) {
                    return seconds + (seconds > 1 ? ' sec ago' : 'just now')
                }

                return 'Just Now'
            },
            // Method for creating dummy notification time
            randomDate({hr, min, sec}) {
                let date = new Date()

                if (hr) date.setHours(date.getHours() - hr)
                if (min) date.setMinutes(date.getMinutes() - min)
                if (sec) date.setSeconds(date.getSeconds() - sec)

                return date
            },
            /*escucharnotificaciones() {
                Echo.private('App.User.' + this.$store.state.esgryma.id)
                    .notification((notification) => {
                        this.notificacionescritorio(notification);
                    });
            },
            notificacionescritorio(datos) {
                Notification.requestPermission(function (permission) {
                    // If the user accepts, let's create a notification
                    if (permission === "granted") {
                        let notification = new Notification(datos.title, {
                            body: datos.body, // content for the alert
                            icon: "/images/logo/logoesgryma.png" // optional image url
                        });
                        // link to page on clicking the notification
                        notification.onclick = () => {
                            window.open(datos.action_url);
                        };
                    }
                });
            },*/
            listarNotificaciones(limit = 20) {
                axios.get('/notifications', {params: {limit}})
                    .then(({data: {total, notifications}}) => {
                        this.total = total
                        this.notifications = notifications.map(({id, data, created_at}) => {
                            return {
                                id: id,
                                titulo: data.title,
                                mensaje: data.body,
                                creada: Date.parse(data.created),
                                url: data.url
                            }
                        })
                    })
            },
            listen() {
                window.Echo.private('App.User.' + this.$store.state.esgryma.id)
                    .notification(notification => {
                        this.total++
                        this.notifications.unshift(notification.map(({id, data, created_at}) => {
                            return {
                                id: id,
                                titulo: data.title,
                                mensaje: data.body,
                                creada: Date.parse(created_at),
                                url: data.url
                            }
                        }))
                    })
                    .listen('NotificationRead', ({notificationId}) => {
                        this.total--
                        const index = this.notifications.findIndex(n => n.id === notificationId)
                        if (index > -1) {
                            this.notifications.splice(index, 1)
                        }
                    })
                    .listen('NotificationReadAll', () => {
                        this.total = 0
                        this.notifications = []
                    })
            },
        }
    }

</script>

