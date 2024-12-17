<template>
    <vx-card title="Lista de planes">
        <descargarExcel id="descargarDatos" v-show="false"
                        class="vs-component vs-button vs-button-border vs-button-primary"
                        :fetch="planReservas"
                        :name="nombreArchivo"
                        :before-generate="startDownload"
                        :before-finish="finishDownload">
            Descargar datos
        </descargarExcel>

        <vs-table :data="datos" pagination :max-items="20" search>
            <template slot="thead">
                <vs-th>ID</vs-th>
                <vs-th>Mes</vs-th>
                <vs-th>Año</vs-th>
                <vs-th>Monto</vs-th>
                <vs-th>Meta</vs-th>
                <!--<vs-th>% Comercial</vs-th>
                <vs-th>% Arl</vs-th>
                <vs-th>Origen</vs-th>-->
                <vs-th class="text-center center d-flex flex justify-center">Acciones</vs-th>
            </template>

            <template slot-scope="{data}">
                <vs-tr :key="indextr" v-for="(tr, indextr) in data">

                    <vs-td :data="data[indextr].id">
                        {{ data[indextr].id }}
                    </vs-td>

                    <vs-td :data="data[indextr].planMes">
                        {{ mesesString(data[indextr].planMes) }}
                    </vs-td>
                    <vs-td :data="data[indextr].planAnio">
                        {{ data[indextr].planAnio }}
                    </vs-td>
                    <vs-td :data="data[indextr].monto">
                        {{ convertirdecimal(data[indextr].monto) }}
                    </vs-td>
                    <vs-td :data="data[indextr].metaMes">
                        {{ convertirdecimal(data[indextr].metaMes) }}
                    </vs-td>
                    <!--<vs-td :data="data[indextr].porComercial">
                        {{ data[indextr].porComercial }}
                    </vs-td>
                    <vs-td :data="data[indextr].porArl">
                        {{ data[indextr].porArl }}
                    </vs-td>
                    <vs-td :data="data[indextr].origen">
                        {{ data[indextr].origen }}
                    </vs-td>-->
                    <vs-td>
                        <vs-row vs-type="flex" vs-justify="flex-center">
                            <vs-col vs-sm="6" vs-md="6" vs-lg="6" vs-type="flex" vs-justify="center"
                                    vs-align="center" vs-w="6">
                                <vx-tooltip :text="'Ver detalle plan '+mesesString(data[indextr].planMes)">
                                    <router-link
                                        :to="{name:'planeacion.planDetalle',query:{anio:data[indextr].planAnio,mes:data[indextr].planMes}}">
                                        <i class='material-icons font-weight-bold'>visibility</i>
                                    </router-link>
                                </vx-tooltip>
                            </vs-col>
                            <vs-col vs-sm="6" vs-md="6" vs-lg="6" vs-type="flex" vs-justify="center"
                                    vs-align="center" vs-w="6">
                                <i class="material-icons"
                                   @click.prevent="obtenerId(data[indextr].planAnio,data[indextr].planMes)">cloud_download</i>
                            </vs-col>
                        </vs-row>
                    </vs-td>
                </vs-tr>
            </template>
        </vs-table>

        <vs-popup class="holamundo" fullscreen
                  :active.sync="poputActivo">
            <vs-table :data="datosDetallePlan" pagination :max-items="20" search>
                <template slot="thead">
                    <vs-th>ID</vs-th>
                    <vs-th>Nro Orden</vs-th>
                    <vs-th>Nro Orden Padre</vs-th>
                    <vs-th>Observación</vs-th>
                    <vs-th>Estado reserva</vs-th>
                    <vs-th>Valor unitario</vs-th>
                    <vs-th>Horas</vs-th>
                    <vs-th>Total</vs-th>
                </template>

                <template slot-scope="{data}">
                    <vs-tr :key="indextr" v-for="(tr, indextr) in data">
                        <vs-td :data="data[indextr].id">
                            {{ data[indextr].id }}
                        </vs-td>
                        <vs-td :data="data[indextr].nroOrden">
                            {{ data[indextr].nroOrden }}
                        </vs-td>
                        <vs-td :data="data[indextr].nroOrdenPadre">
                            {{ data[indextr].nroOrdenPadre }}
                        </vs-td>
                        <vs-td :data="data[indextr].obsOrden">
                            {{ data[indextr].obsOrden }}
                        </vs-td>
                        <vs-td :data="data[indextr].estadoReserva">
                            {{ data[indextr].estadoReserva }}
                        </vs-td>
                        <vs-td :data="data[indextr].valorunitario">
                            {{ convertirdecimal(data[indextr].valorunitario) }}
                        </vs-td>
                        <vs-td :data="data[indextr].horas">
                            {{ data[indextr].horas }}
                        </vs-td>
                        <vs-td :data="data[indextr].valor">
                            {{ convertirdecimal(data[indextr].valor) }}
                        </vs-td>
                    </vs-tr>
                </template>
            </vs-table>
        </vs-popup>
    </vx-card>
</template>

<script>
    import descargarExcel from "vue-json-excel";

    export default {
        name: "planes",
        data() {
            return {
                datos: [],
                poputActivo: false,
                datosDetallePlan: [],
                anio: null,
                mes: null,
                nombreArchivo: ''
            }
        },
        components: {
            descargarExcel
        },
        created() {
            this.consultarPlanes();
        },
        methods: {
            consultarPlanes() {
                this.cargandoGeneral = true;
                axios.post("/planificacion/consultarListaPlanes")
                    .then(res => {
                        this.datos = res.data;
                        this.cargandoGeneral = false;
                    })
            },
            mesesString(mes) {
                return this.meses[mes - 1];
            },
            consultarDetallePlan(idPlan) {
                this.cargandoGeneral = true;
                axios.post("/planificacion/consultarDetallePlan", {id: idPlan})
                    .then(res => {
                        this.datosDetallePlan = res.data;
                        this.poputActivo = true;
                        this.cargandoGeneral = false;
                    })
            },
            obtenerId(anio, mes) {
                this.anio = anio;
                this.mes = mes;
                this.nombreArchivo = 'PlanReserva_' + anio + "_" +this.meses[mes-1]+".xls";
                $("#descargarDatos").click();
            },
            async planReservas() {
                const response = await axios.post('/planificacion/planReservas', {"anio": this.anio, mes: this.mes});
                return response.data;
            },
            startDownload() {
                this.cargandoGeneral = true;
            },
            finishDownload() {
                this.cargandoGeneral = false;
            }
        }
    }
</script>

<style scoped>

</style>
