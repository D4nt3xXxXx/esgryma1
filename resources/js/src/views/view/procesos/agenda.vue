<template>
    <vs-row>
        <vs-card title="Leyendas" v-if="esGestor || idGestor!=null">
            <vs-row>
                <vs-col vs-sm="12" vs-md="2" vs-lg="2" v-for="(label, index) in leyendas" :key="index" class="mb-2">
                    <div class="h-3 w-3 inline-block rounded-full mr-2"
                         :style="'background-color:'+label.color"></div>
                    <span>{{ label.titulo }}</span>
                </vs-col>
            </vs-row>
        </vs-card>
        <vs-card title="Leyendas" v-if="!esGestor && idGestor==null">
            <vs-row>
                <vs-col vs-sm="6" vs-md="2" vs-lg="2" v-for="(item,index) in listaGestores" class="mb-2">
                    <vs-checkbox @input="actualizarVista" :key="index"
                                 :vs-value="item.id" :color="item.color"
                                 v-model="selectGestores">{{item.text}}
                    </vs-checkbox>
                </vs-col>
            </vs-row>
            <vs-row>
                <vs-col vs-sm="12" vs-md="12" vs-lg="12">
                    <hr>
                </vs-col>
            </vs-row>
            <vs-row>
                <vs-col vs-sm="6" vs-md="2" vs-lg="2" v-for="(item,index) in listaLeyendas" class="mb-2">
                    <vs-checkbox @input="actualizarVista" :key="index"
                                 :vs-value="item.id" :color="item.color"
                                 v-model="leyendasSeleccionadas">{{item.text}}
                    </vs-checkbox>
                </vs-col>
            </vs-row>
        </vs-card>
        <full-calendar @actualizarEventos="actualizarVista" :eventos="datos"></full-calendar>

    </vs-row>
</template>

<script>
    import FullCalendar from '../componentes/calendario/fullCalendar'

    var vm = null;
    export default {
        name: "agenda",
        components: {FullCalendar},
        data() {
            return {
                datos: [],
                datosOriginal: [],
                idGestor: null,
                leyendas: [],
                leyendasSeleccionadas: [],
                esGestor: true,
                listaGestores: [],
                listaLeyendas: [],
                selectGestores: []
            }
        },
        created() {
            this.esGestor = this.isRole('gestor');
            this.idGestor = this.$route.query.idGestor;
            if (this.esGestor || this.idGestor != undefined)
                this.listarEventosAgendaGestor();
            else
                this.listarEventosAgenda();
        },
        mounted() {
            vm = this;
            if (this.idGestor != undefined) {
                $(".vx-navbar-wrapper").append().hide();
                $(".vx-navbar-wrapper").append().hide();
            }
        },
        methods: {
            listarEventosAgenda() {
                axios.post("/gestor/listarEventosAgenda", {idGestor: this.idGestor})
                    .then(res => {
                        this.datosOriginal = res.data[0];
                        this.leyendas = res.data[1];
                        this.listaGestores = res.data[1];
                        this.listaLeyendas = res.data[2];
                    })
            },
            listarEventosAgendaGestor() {
                axios.post("/gestor/listarEventosAgendaGestor", {idGestor: this.idGestor})
                    .then(res => {
                        this.datosOriginal = res.data[0];
                        this.leyendas = res.data[1];
                        this.datos = res.data[0];
                    })
            },
            filtroEventosAgenda() {
                axios.post("/gestor/filtroEventosAgenda", {
                    gestores: this.selectGestores,
                    datos: this.datosOriginal,
                    listaGestores: this.listaGestores
                })
                    .then(res => {
                        this.datos = res.data.datos;
                        this.listaLeyendas = res.data.leyendas;
                        this.leyendasSeleccionadas = res.data.selectLeyendas;
                        this.actualizarVista();
                    })
            },
            actualizarVista() {
                var temp = [];
                var gestoresOcultar
                $.each(vm.listaGestores, function (index, value) {
                    temp.push(Object.assign({}, value))
                });

                var data3 = [];
                $.each(vm.listaGestores, function (index, objeto) {

                    var temp_existe = $.inArray(objeto.id, vm.selectGestores);

                    if (temp_existe === -1) {
                        data3.push(objeto);
                    } else {
                        $("." + objeto.id).show();
                    }

                });
                $.each(vm.listaLeyendas, function (index, objeto) {

                    var temp_existe = $.inArray(objeto.id, vm.leyendasSeleccionadas);

                    if (temp_existe === -1) {
                        data3.push(objeto);
                    } else {
                        $("." + objeto.id).show();
                    }
                });
                $.each(data3, function (index, value) {
                    $("." + value.id).hide();
                })
            }
        },
        watch: {
            selectGestores(val) {
                if (this.selectGestores.length > 0) {
                    this.filtroEventosAgenda();
                } else {
                    this.leyendasSeleccionadas = [];
                }
            }
        }
    }
</script>

<style scoped>

</style>
