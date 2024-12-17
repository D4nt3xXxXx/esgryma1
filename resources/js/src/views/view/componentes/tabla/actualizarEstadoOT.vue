<template>
    <vs-row vs-align="flex-end" vs-type="flex" vs-justify="end" vs-w="12">
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-w="12">
            <a @click.prevent="abrir(params.data.idReserva)">
                <i class="material-icons">search</i>
            </a>
        </vs-col>
    </vs-row>
</template>

<script>
    var vm;
    export default {
        name: "actualizarEstadoOT",
        data() {
            return {
                revisado: false,
                idReserva: null
            }
        },
        mounted() {
            this.vm = this;
            this.idReserva = this.params.data.idReserva;
        },
        methods: {
            guardarOrdenValidada(idReserva, idOT) {
                this.$swal.fire({
                    title: 'Validar O.T?',
                    text: "Está seguro que desea guardar los cambios ?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, guardar!'
                }).then((result) => {
                    if (result.value) {
                        vm.activeLoading = true;
                        var estadoReserva = 14;
                        var estadoOT = 17;
                        var mensaje = "La orden de trabajo a cambiado al estado de REALIZADA";

                        axios.post("/gestor/guardarOrdenRealizada", {
                            "idReserva": idReserva,
                            "idOT": idOT,
                            "estadoOT": estadoOT,
                            "estadoReserva": estadoReserva
                        })
                            .then(res => {
                                this.activeLoading = false;
                                this.mostrarNotificacion("Registro exitoso", mensaje, 'success');
                                this.$parent.$parent.consultarOtRevision();
                            })
                    }
                })

            },
            abrir(idReserva) {
                // this.$emit("changePoput", true,idReserva);
                this.$parent.$parent.actualizarPoput(true,idReserva);
            }
        },
        watch: {
            abrirPoput: function (val) {
                if (val == true) {
                    this.$refs.componente_ver_ot.obtenerreservaId(this.idReserva);
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
