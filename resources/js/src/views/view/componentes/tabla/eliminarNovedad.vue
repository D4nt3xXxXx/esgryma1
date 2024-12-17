<template>
    <div class="flex items-center">
        <span @click="editarNovedad(params.data)"
              class="text-inherit text-info hover:text-primary cursor-pointer">
                <span class="material-icons">edit</span>
            </span>
        <span @click="eliminarNovedadId(params.data.idNovedad)"
              class="text-inherit text-danger hover:text-primary cursor-pointer">
                <span class="material-icons">delete</span>
            </span>
    </div>
</template>

<script>
    import {DateTime} from 'luxon'

    export default {
        name: "eliminarNovedad",
        data() {
            return {
                idNovedad: null
            }
        },
        methods: {
            eliminarNovedadId(val) {
                this.idNovedad = val;
                this.$vs.dialog({
                    type: 'confirm',
                    color: 'danger',
                    title: `Eliminar novedad`,
                    text: 'Desea eliminar la novedad seleccionada ?',
                    accept: this.eliminar
                })

            },
            eliminar(val) {
                this.cargandoGeneral = true;
                axios.post("/procesos/novedad.eliminar", {id: this.idNovedad})
                    .then(res => {
                        this.cargandoGeneral = false;
                        this.mostrarNotificacion("Registro eliminado", "La novedad se ha eliminado con exito!", "success");
                        location.reload();
                    })
            },
            editarNovedad(item) {
                this.$parent.$parent.infoEditNovedad(item);
            }
        }
    }
</script>

<style scoped>

</style>
