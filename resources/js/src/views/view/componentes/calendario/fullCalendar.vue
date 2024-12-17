<template>
    <vs-card>
        <div class="vx-row no-gutter">
            <!-- Month Name -->
            <div class="vx-col w-1/3 items-center sm:flex hidden">
                <!-- Add new event button -->
            </div>
            <!-- Current Month -->
            <div class="vx-col sm:w-1/3 w-full sm:my-0 my-3 flex sm:justify-end justify-center order-last">
                <div class="flex items-center">
                    <feather-icon
                        :icon="$vs.rtl ? 'ChevronRightIcon' : 'ChevronLeftIcon'"
                        @click="updateMonth(-1)"
                        svgClasses="w-5 h-5 m-1"
                        class="cursor-pointer bg-primary text-white rounded-full"/>

                    <span class="mx-3 text-xl font-medium whitespace-no-wrap">{{ tituloSiguiente }}</span>

                    <feather-icon
                        :icon="$vs.rtl ? 'ChevronLeftIcon' : 'ChevronRightIcon'"
                        @click="updateMonth(1)"
                        svgClasses="w-5 h-5 m-1"
                        class="cursor-pointer bg-primary text-white rounded-full"/>
                </div>
            </div>

            <div class="vx-col sm:w-1/3 w-full flex justify-center">
                <template v-for="(view, index) in calendarViewTypes">
                    <vs-button
                        v-if="calendarView === view.val"
                        :key="String(view.val) + 'filled'"
                        type="filled"
                        class="p-3 md:px-8 md:py-3"
                        :class="{'border-l-0 rounded-l-none': index, 'rounded-r-none': calendarViewTypes.length !== index+1}"
                        @click="actualizarVistas(view.val)"
                    >{{ view.label }}
                    </vs-button>
                    <vs-button
                        v-else
                        :key="String(view.val) + 'border'"
                        type="border"
                        class="p-3 md:px-8 md:py-3"
                        :class="{'border-l-0 rounded-l-none': index, 'rounded-r-none': calendarViewTypes.length !== index+1}"
                        @click="actualizarVistas(view.val)"
                    >{{ view.label }}
                    </vs-button>
                </template>

            </div>
        </div>
        <FullCalendar :locale="local"
                      :header="{
                          left: 'today',
            center: '',
            right: ''
            }"
                      time-zone='America/Bogota' defaultView="dayGridMonth"
                      :events="eventos"
                      ref="calendar" schedulerLicenseKey="0199165136-fcs-1577368450"
                      :plugins="calendarPlugins"
                      :navLinks="true"
                      week-numbers="true"
                      event-limit="true"
                      now-indicator="true"
                      :event-render="mostrarTooltip"
                      :now="fechaActual"
                      @dateClick="handleDateClick"
                      @eventClick="infoEvento"
        />

        <vs-popup fullscreen classContent="popup-example"
                  :title="opcion=='OT' ? 'Información orden de trabajo ' : 'Información de novedad'"
                  :active.sync="abrirPoput">
            <div v-if="opcion=='OT'">
                <ordenes-gestion-view ref="componente_gestionar_orden"
                                      :id-reserva="idReserva"></ordenes-gestion-view>
            </div>
            <div v-else>
                <ver-novedad ref="componente_novedad" :buscarReserva="buscar" :id-novedad="idNovedad"></ver-novedad>
            </div>

        </vs-popup>

    </vs-card>
</template>

<script>
import FullCalendar from '@fullcalendar/vue'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import esLocale from '@fullcalendar/core/locales/es';
import moment from 'moment'
import {toMoment} from '@fullcalendar/moment'; // only for formatting
import momentTimezonePlugin from '@fullcalendar/moment-timezone/main';
import ordenesGestionView from '../../ordenes/ordenes_gestion_edit'
import verNovedad from '../../procesos/novedades/verNovedad'

export default {
    name: "fullCalendar",
    props: {
        titulo: '',
        eventos: {
            type: [Array, Object]
        }
    },
    components: {FullCalendar, ordenesGestionView, verNovedad},
    data: function () {
        return {
            fechaActual: new Date(),
            opcion: 'OT',
            fechaInicio: null,
            fechaFin: null,
            buscar: null,
            file: null,
            abrirPoput: false,
            idReserva: null,
            idNovedad: null,
            calendarPlugins: [
                dayGridPlugin,
                listPlugin,
                timeGridPlugin
            ],
            calendarWeekends: true,
            calendarEvents: [ // initial event data
                // {title: 'Event Now', start: new Date()}
            ],
            local: esLocale,
            config: {
                editable: false,
                defaultView: 'month',
                selectable: true,
                timeZone: 'America/Bogota',
            },
            calendarView: 'dayGridMonth',
            calendarViewTypes: [
                {
                    label: "Mes",
                    val: "dayGridMonth"
                },
                {
                    label: "Semana",
                    val: "timeGridWeek"
                },
                {
                    label: "Día",
                    val: "timeGridDay"
                },
                {
                    label: "Agenda",
                    val: "listWeek"
                },
            ],
            tituloSiguiente: ''
        }
    },
    computed: {
        windowWidth() {
            return this.$store.state.windowWidth
        }
    },
    methods: {
        updateMonth(val) {
            let calendarApi = this.$refs.calendar.getApi();
            if (val > 0)
                calendarApi.next()
            else
                calendarApi.prev();

            this.tituloSiguiente = calendarApi.view.view.title;
            this.$emit("actualizarEventos");
        },
        toggleWeekends() {
            this.calendarWeekends = !this.calendarWeekends // update a property
        },
        gotoPast() {
            let calendarApi = this.$refs.fullCalendar.getApi() // from the ref="..."
            calendarApi.gotoDate(new Date()) // call a method on the Calendar object
        },
        handleDateClick(arg) {
            /*if (confirm('Would you like to add an event to ' + arg.dateStr + ' ?')) {
                this.calendarEvents.push({ // add new event data
                    title: 'New Event',
                    start: arg.date,
                    allDay: arg.allDay
                })
            }*/
        },
        infoEvento(info) {
            if (info.event.extendedProps.tipo == 'OT') {
                this.opcion = 'OT';
                this.buscar = 'no';
                const id = info.event.id;
                this.idReserva = parseInt(id);
            } else {
                this.opcion = 'RESERVA';
                this.buscar = 'si';
                this.fechaInicio = info.event.start;
                this.fechaFin = info.event.end;
                this.idNovedad = parseInt(info.event.id);
            }

            this.abrirPoput = true;
        },
        actualizarVistas(val) {
            this.$refs.calendar.getApi().changeView(val);
            this.calendarView = val;
            this.tituloSiguiente = this.$refs.calendar.getApi().view.view.title;
            this.$emit("actualizarEventos");
        },
        mostrarTooltip(info) {

        }
    },
    mounted() {
        this.tituloSiguiente = this.$refs.calendar.getApi().view.view.title;
        var vm = this;
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            /*this.$refs.fullCalendar.changeView('agendaDay');*/
            vm.$refs.calendar.getApi().changeView('listWeek');
        }
        console.log(new Date());
    },
    watch: {
        abrirPoput: function (val) {
            if (!val) {
                this.idReserva = null;
                this.buscar = null;
            }
        }
    }
}
</script>

<style lang='scss'>

@import '~@fullcalendar/core/main.min.css';
@import '~@fullcalendar/list/main.min.css';
@import '~@fullcalendar/daygrid/main.min.css';
@import '~@fullcalendar/timegrid/main.min.css';

</style>
