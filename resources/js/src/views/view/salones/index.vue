<template>
    <vs-row>
        <vs-card title="Leyendas" v-if="idSalon!=null">
            <vs-row>
                <vs-col vs-sm="12" vs-md="2" vs-lg="2" v-for="(label, index) in leyendas" :key="index" class="mb-2">
                    <div class="h-3 w-3 inline-block rounded-full mr-2"
                         :style="'background-color:'+label.color"></div>
                    <span>{{ label.text }}</span>
                </vs-col>
            </vs-row>
        </vs-card>
        <vs-card title="Leyendas" v-if="idSalon==null">
            <vs-row>
                <vs-col vs-sm="6" vs-md="2" vs-lg="2" v-for="item,index in listaSalones" class="mb-2">
                    <vs-checkbox @input="actualizarVista" :key="index"
                                 :vs-value="item.id" :color="item.color"
                                 v-model="selectSalones">{{item.text}}
                    </vs-checkbox>
                </vs-col>
            </vs-row>
        </vs-card>
        <full-calendar @actualizarEventos="actualizarVista" :eventos="datos"></full-calendar>

    </vs-row>
</template>

<script>
    import FullCalendar from '../componentes/calendario/fullCalendar'

    var vm;
    export default {
        name: "index",
        components: {FullCalendar},
        data() {
            return {
                datos: [],
                idSalon: null,
                selectSalones: [],
                leyendas: [],
                listaSalones: []
            }
        },
        created() {
            this.idSalon = this.$route.query.idSalon;
            vm = this;
            this.listarEventosSalon();
        },
        methods: {
            listarEventosSalon() {
                axios.post("/procesos/listarEventosSalones", {idSalon: this.idSalon})
                    .then(res => {
                        this.datos = res.data[0];
                        this.leyendas = res.data[1];
                        vm = this;
                        this.listaSalones = res.data[1];
                        $.each(this.listaSalones, function (index, value) {
                            vm.selectSalones.push(value.id);
                        })

                    })
            },
            actualizarVista() {
                var temp = [];
                var gestoresOcultar
                $.each(vm.listaSalones, function (index, value) {
                    temp.push(Object.assign({}, value))
                });

                var data3 = [];

                $.each(vm.listaSalones, function (index, objeto) {

                    var temp_existe = $.inArray(objeto.id, vm.selectSalones);

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
    }
</script>

<style scoped>

</style>
