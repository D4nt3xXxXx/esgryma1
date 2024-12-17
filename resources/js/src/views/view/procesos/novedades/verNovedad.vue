<template>
    <ValidationObserver ref="form" v-if="Object.keys(datos).length>0">
        <form>
            <div class="vx-row">
                <div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 w-full mb-2">
                    <vs-input label="Novedad" v-model="datos.novedad"
                              class="w-full"/>
                </div>
                <div class="vx-col sm:w-12/12 md:w-12/12 lg:w-12/12 w-full mb-2">
                    <vs-chip v-for="item in datos.gestor.split(',')">
                        <vs-avatar/>
                        {{ item }}
                    </vs-chip>
                </div>
            </div>
            <div class="vx-row">
                <div class="vx-col sm:w-12/12 md:w-6/12 lg:w-6/12 w-full mb-2">
                    <vs-input label="Fecha inicio" v-model="datos.fechaInicio"
                              class="w-full"/>
                </div>
                <div class="vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 w-full mb-2">
                    <vs-input label="Fecha fin" v-model="datos.fechaFin"
                              class="w-full"/>
                </div>
                <div class="vx-col sm:w-12/12 md:w-2/12 lg:w-2/12 w-full mb-2">
                    <vs-input label="Horas" v-model="datos.horas"
                              class="w-full"/>
                </div>
                <div class="vx-col sm:w-12/12 md:w-12/12 lg:w-12/12 w-full mb-2">
                    <vs-input label="Observación" v-model="datos.observacion"
                              class="w-full"/>
                </div>
            </div>
            <!--<div class="vx-row">
                <div class="vx-col w-full text-center">
                    <boton texto_cargando="Guardando actividad..." type="submit"
                           texto="Guardar actividad" :cargando="activeLoading"></boton>
                </div>
            </div>-->
        </form>
    </ValidationObserver>
</template>

<script>
export default {
    name: "verNovedad",
    props: {
        buscarReserva: {required: true},
        idNovedad: {type: Number}
    },
    data() {
        return {
            datos: []
        }
    },
    methods: {
        consultarNovedad() {
            axios.post("/procesos/consultarNovedad", {
                idNovedad:this.idNovedad
            })
                .then(res => {
                    this.datos = res.data[0];
                })
        }
    },
    watch: {
        buscarReserva: {
            immediate: true,
            handler(val, oldVal) {
                if (this.buscarReserva == 'si')
                    if (this.idNovedad != '' && this.idNovedad != null) {
                        this.consultarNovedad();
                    }
            }
        }
    }
}
</script>

<style scoped>

</style>
