<template>

    <div class='container-fluid'>
        <!--<div class="row">
            <input type="file" id="file" ref="file" v-on:change="handleFileUpload()"/>
            <button class="btn btn-primary" @click="importar">Import</button>
        </div>-->

        <FullCalendar :themeSystem="'bootstrap4'"
                      class='row'
                      ref="fullCalendar"
                      defaultView="timeGridWeek"
                      :header="{
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
      }"
                      :plugins="calendarPlugins"
                      :weekends="calendarWeekends"
                      :events="calendarEvents"
                      :locale="local"
                      @dateClick="handleDateClick"
        />
    </div>
</template>

<script>
    import FullCalendar from '@fullcalendar/vue'
    import dayGridPlugin from '@fullcalendar/daygrid'
    import timeGridPlugin from '@fullcalendar/timegrid'
    import interactionPlugin from '@fullcalendar/interaction'
    import listPlugin from '@fullcalendar/list';
    import esLocale from '@fullcalendar/core/locales/es';
    import {Calendar} from '@fullcalendar/core';
    import bootstrapPlugin from '@fullcalendar/bootstrap';

    export default {
        components: {
            FullCalendar // make the <FullCalendar> tag available
        },
        data: function () {
            return {
                calendarPlugins: [ // plugins must be defined in the JS
                    dayGridPlugin,
                    timeGridPlugin,
                    interactionPlugin, // needed for dateClick
                    listPlugin,
                    bootstrapPlugin
                ],
                calendarWeekends: true,
                calendarEvents: [ // initial event data
                    {title: 'Event Now', start: new Date()}
                ],
                local: esLocale,
                config: {
                    locale: 'pt-br',
                    editable: false,
                    defaultView: 'month',
                    selectable: true,
                    themeSystem: 'bootstrap'
                },
                file: null
            }
        },
        methods: {
            toggleWeekends() {
                this.calendarWeekends = !this.calendarWeekends // update a property
            },
            gotoPast() {
                let calendarApi = this.$refs.fullCalendar.getApi() // from the ref="..."
                calendarApi.gotoDate(new Date()) // call a method on the Calendar object
            },
            handleDateClick(arg) {
                if (confirm('Would you like to add an event to ' + arg.dateStr + ' ?')) {
                    this.calendarEvents.push({ // add new event data
                        title: 'New Event',
                        start: arg.date,
                        allDay: arg.allDay
                    })
                }
            },
            importar() {
                let formData = new FormData();
                formData.append('file', this.file);
                axios.post( '/import',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then(function(data){
                    console.log(data);
                })
                    .catch(function(){
                        console.log('FAILURE!!');
                    });
            },
            handleFileUpload() {
                this.file = this.$refs.file.files[0];
            }
        },
        mounted() {
            if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                /*this.$refs.fullCalendar.changeView('agendaDay');*/
                this.$refs.fullCalendar.getApi().changeView('listWeek');
            }
        }
    }
</script>

<style lang='scss'>
    // you must include each plugins' css
    // paths prefixed with ~ signify node_modules
    @import '~@fullcalendar/core/main.css';
    @import '~@fullcalendar/daygrid/main.css';
    @import '~@fullcalendar/timegrid/main.css';

    .demo-app {
        font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
        font-size: 14px;
    }

    .demo-app-top {
        margin: 0 0 3em;
    }

    .demo-app-calendar {
        margin: 0 auto;
        max-width: 900px;
    }
</style>
