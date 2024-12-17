<template>
    <ValidationObserver v-slot="{ handleSubmit }" ref="formNovedad"
                        tag="form">
        <form @submit.prevent="handleSubmit(subirDocumento)">
            <div class="vx-row">
                <div class="vx-col sm:w-12/12 md:w-12/12 lg:w-12/12 w-full d-flex flex justify-center">
                    <vs-upload :data="datosDcto" ref="archivo" id="archivos" :show-upload-button="false"
                               action="https://jsonplaceholder.typicode.com/po/sdsdsd"/>
                </div>
                <div class="vx-col sm:w-12/12 md:w-12/12 lg:w-12/12 w-full d-flex flex justify-center justify-content-center">
                    <boton texto_cargando="Guardando archivo..." type="submit"
                           texto="Guardar archivo" :cargando="loading"></boton>
                </div>
            </div>
        </form>
    </ValidationObserver>
</template>

<script>
    export default {
        name: "subirDocumento",
        props: {
            datosReserva: {
                type: [Array, Object],
                required: true
            }
        },
        data() {
            return {
                datosDcto: []
            }
        },
        methods: {
            obtenerDatos(archivo) {
                this.datosDcto.push(archivo);
            },
            subirDocumento() {
                var archivos = this.$refs.archivo.filesx;
                if (archivos.length > 0) {
                    const formData = new FormData();
                    archivos.forEach(archivos => {
                        formData.append('archivo[]', archivos);
                    });
                    $.each(this.datosReserva, function (index, value) {
                        formData.append(index, value);
                    });
                    axios.post("/documentos/guardarDocumento", formData)
                        .then(res => {
                            this.$refs.archivo.filesx = [];
                            this.mostrarNotificacion("Subida exitosa", "Se ha subido el documento con exito!", "success");
                            this.$emit("actualizarVista");
                        })
                } else {
                    this.mostrarNotificacion("Sin documento", "Debe seleccionar uno o varios documentos!", "warning");
                }
            }
        },
    }
</script>

<style scoped>

</style>
