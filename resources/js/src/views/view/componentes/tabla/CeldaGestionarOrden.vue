<template>
    <div class="flex items-center">
        <vx-tooltip text="Gestionar orden">
            <span @click="abrirPoput=true" class="text-inherit hover:text-primary cursor-pointer">
                <span class="material-icons">new_releases</span>
            </span>
        </vx-tooltip>
        <vx-tooltip text="Segementar reserva"
                    v-if="params.data.idEstadoReserva==2 && params.data.nroOrden==params.data.nroOrdenPadre">
            <span @click="abrirPoputSegmentar=true" class="text-inherit hover:text-primary cursor-pointer">
                <span class="material-icons">list_alt</span>
            </span>
        </vx-tooltip>
        <vs-popup fullscreen classContent="popup-example"
                  :title="'Generación orden de trabajo '/*+params.data.nroOrden*/ "
                  :active.sync="abrirPoput">
            <ordenes-gestion-edit ref="componente_gestionar_orden"
                                  :id-reserva="abrirPoput ? params.data.idReserva : null"
                                  @cerrarPoput="cerrarPoput"></ordenes-gestion-edit>
        </vs-popup>
        <vs-popup classContent="popup-example" :title="'Segmentar reserva '+params.data.nroOrden "
                  :active.sync="abrirPoputSegmentar">
            <reserva-edit ref="componente_segmentar_reserva_asis" opcion="asistente"
                          :id-reserva="params.data.idReserva"></reserva-edit>
        </vs-popup>
    </div>
</template>

<script>
    import ordenesGestionEdit from '../../ordenes/ordenes_gestion_edit'
    import reservaEdit from '../../reservas/reserva_edit'

    export default {
        name: "CeldaGestionarOrden",
        components: {ordenesGestionEdit, reservaEdit},
        data() {
            return {
                abrirPoput: false,
                abrirPoputSegmentar: false
            }
        },
        methods: {
            cerrarPoput(val) {
                if (val)
                    this.abrirPoput = true;
                else
                    this.abrirPoput = false;
            },
            eliminarReserva(idReserva) {

            }
        },
        watch: {
            abrirPoput: function (val) {
                if (val == true) {
                    this.$refs.componente_gestionar_orden.obtenerreservaId();
                }
            },
            abrirPoputSegmentar: function (val) {
                if (val == true) {
                    this.$refs.componente_segmentar_reserva_asis.obtenerreservaId();
                    /* this.$root.$emit("obtenerreservaId");
                     const eventBus = new Vue ();
                     eventBus.$emit('obtenerreservaId');*/
                }
            },
        }
    }
</script>

<style scoped>

</style>
