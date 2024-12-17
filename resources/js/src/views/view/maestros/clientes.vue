<template>
    <div class="container-fluid">
        <div class="row">
            <div class="vx-col w-full mb-base">
                <vx-card title="">

                    <div class="flex flex-wrap items-center mb-3">
                        <vs-button color="warning" type="filled" @click="popupActivo=true;datosCliente=[];tipo='nuevo'">
                            Agregar cliente
                        </vs-button>
                        <vs-popup class="holamundo" title="Crear cliente" :active.sync="popupActivo">
                            <clientes-agregar :tipo="tipo" :datos-cliente="datosCliente"></clientes-agregar>
                        </vs-popup>
                    </div>

                    <vs-table :data="this.listaClientes" pagination :max-items="10" search>
                        <template slot="thead">
                            <vs-th>Id</vs-th>
                            <vs-th>Nit</vs-th>
                            <vs-th>Nombre</vs-th>
                            <vs-th>Tipo</vs-th>
                            <vs-th>Acciones</vs-th>
                        </template>

                        <template slot-scope="{data}">
                            <vs-tr :key="indextr" v-for="(tr, indextr) in data">

                                <vs-td :data="data[indextr].id">
                                    {{ data[indextr].id }}
                                </vs-td>

                                <vs-td :data="data[indextr].nit">
                                    {{ data[indextr].nit }}
                                </vs-td>

                                <vs-td :data="data[indextr].nombre">
                                    {{ data[indextr].nombre }}
                                </vs-td>

                                <vs-td :data="data[indextr].tipo">
                                    {{ data[indextr].tipo }}
                                </vs-td>

                                <vs-td>
                                    <vs-row vs-type="flex" vs-justify="flex-end">
                                        <vs-col vs-sm="6" vs-md="6" vs-lg="6" vs-type="flex" vs-justify="end"
                                                vs-align="end" vs-w="6">
                                            <vx-tooltip text="Editar cliente">
                                                <span @click="actualizarCliente(data[indextr])"
                                                      class="cursor-pointer"><i class="bx bx-edit-alt"></i></span>
                                            </vx-tooltip>

                                        </vs-col>
                                        <vs-col vs-sm="6" vs-md="6" vs-lg="6" vs-type="flex" vs-justify="end"
                                                vs-align="end" vs-w="6">
                                            <vx-tooltip text="Eliminar cliente">
                                                <span @click="abrirEliminarCliente(data[indextr])"
                                                      class="cursor-pointer"><i class="bx bxs-tag-x"></i></span>
                                            </vx-tooltip>
                                        </vs-col>
                                    </vs-row>
                                </vs-td>
                            </vs-tr>
                        </template>

                    </vs-table>
                </vx-card>
            </div>
        </div>
    </div>
</template>

<script>
    import clientesAgregar from './clientes_agregar'
    import Tooltip from "../../components/vuesax/tooltip/Tooltip";

    export default {
        name: "clientes",
        components: {Tooltip, clientesAgregar},

        data() {
            return {
                listaClientes: [],
                popupActivo: false,
                datosCliente: [],
                tipo: '',
                poputEliminarCliente: false,
                idCliente: null
            }
        },

        created() {
            this.getClientes();
        },

        methods: {
            getClientes() {
                this.cargandoGeneral = true;
                axios.post("/reservas/listaCliente")
                    .then(res => {
                        this.cargandoGeneral = false;
                        this.listaClientes = res.data;
                    })
            },
            actualizarCliente(datos) {
                this.datosCliente = datos;
                this.tipo = 'actualizar';
                this.popupActivo = true;
            },
            abrirEliminarCliente(datos) {
                this.poputEliminarCliente = true;
            },

            abrirEliminarCliente(datos) {
                this.idCliente = datos.id;
                this.$vs.dialog({
                    type: 'confirm',
                    color: 'danger',
                    title: `Eliminar cliente`,
                    text: 'Desea eliminar el cliente seleccionado ?',
                    accept: this.eliminarCliente
                })
            },
            eliminarCliente() {
                axios.post("/maestros/eliminarCliente", {idCliente: this.idCliente})
                    .then(res => {
                        if (res.data == 'ok') {
                            this.mostrarNotificacion('Eliminación cliente', "Cliente eliminado con exito!", 'success')
                            this.getClientes();
                        }
                        else
                            this.mostrarNotificacion('Eliminación cliente', res.data, 'warning')
                    })
            },
        },

    }
</script>

<style scoped>

</style>