window.temp_cargando = null;
var moment = require('moment-timezone');
const mixin = {
    data() {
        return {
            rolSeleccionado: '',
            loading: false,
            activeLoading: false,
            cargandoGeneral: false,
            dias: ["Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado", "Domingo"],
            meses: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"]
        }
    },
    methods: {
        isRole(role) {
            if (role == "*")
                return true;
            else {
                if (this.validarvariable(this.$store.state.esgryma.name) != 0) {
                    if (this.$store.state.esgryma.roles.length > 0) {
                        var roles = role.split('|');
                        var res = false;
                        for (var i = 0; i <= roles.length; i++) {
                            if (this.array_has(this.$store.state.esgryma.roles, roles[i])) {
                                res = true;
                            }
                        }

                        return res;
                    } else {
                        return false;
                    }
                }
            }
        },
        canPermiso(permiso) {
            if (permiso == "*")
                return true;
            else {
                if (this.validarvariable(this.$store.state.esgryma.name) != 0) {
                    if (this.$store.state.esgryma.permissions.length > 0) {
                        var permisos = permiso.split('|');
                        var res = false;
                        for (var i = 0; i <= permisos.length; i++) {
                            if (this.array_has(this.$store.state.esgryma.permissions, permisos[i])) {
                                res = true;
                            }
                        }

                        return res;
                    } else {
                        return false;
                    }
                }
            }
        },
        validarvariable(variable) {
            if (variable != undefined) {
                return variable;
            } else {
                return 0;
            }
        },
        array_has(arr, obj) {
            return (arr.findIndex(x => x.slug === obj) != -1);
        },
        convertirdecimal(valor) {
            if (valor != null && valor != undefined && valor != '') {
                if (valor == 0) {
                    return "0";
                } else
                    return parseFloat(valor).toLocaleString('de-DE');
            }

            return "0";
        },
        solonumeros(evt) {
            var nav4 = window.Event ? true : false;
            // NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57
            var key = nav4 ? evt.which : evt.keyCode;
            var res = (key <= 13 || (key >= 48 && key <= 57) || (key >= 96 && key <= 105));
            if (!res) {
                evt.preventDefault();
            } else
                return true;
        },
        mostrarNotificacion(titulo, text, tipo) {
            this.$vs.notify({
                title: titulo,
                text: text,
                color: tipo
            });
        },
        isEmpty(val) {
            if (val != undefined) {
                if (typeof val == 'string') val = val.trim();
                if (val != '' && val != null)
                    return false;
            }

            return true;
        },
        truncarTexto(val, len = 20, punto = '...') {
            if (val != '' && val != null)
                return val.length > len ? val.substring(0, len) + punto : val;

            return val;
        },
        diaSemana2(param_fecha, hora) {
            if (param_fecha != '' && param_fecha != null) {
                //console.log(x.value);
                //Ya no se obtiene del new Date
                let date = moment(param_fecha, 'YYYY-MM-DD HH:mm').tz("America/Bogota");
                //var fechaNum = date.getDate() + 1;
                //var mes_name = date.getMonth();
                //console.log(date);
                // console.log(date.getDate());

                //Obtengo directo del string y no de la variable tipo fecha
                var fechaString = param_fecha;
                var mes_name = parseInt(fechaString.substring(5, 7)) - 1;

                var fechaNum = fechaString.substring(8, 10);

                var year = fechaString.substring(0, 4);
                const dia = date.day();
                return this.dias[dia > 0 ? dia - 1 : 6].substring(0, 3) + " " + fechaNum + ' de ' + this.meses[mes_name].substring(0, 3) + ", " + year + (hora ? ', ' + this.formatAMPM(date) : '' +
                    '');
            } else
                return '';

        },
        formatAMPM(date) {
            var hours = date.duration().hours();
            var minutes = date.duration().minutes();
            var ampm = hours >= 12 ? 'pm' : 'am';
            hours = hours % 12;
            hours = hours ? hours : 12; // the hour '0' should be '12'
            minutes = minutes < 10 ? '0' + minutes : minutes;
            var strTime = hours + ':' + minutes + ' ' + ampm;
            return strTime;
        },
    },
    watch: {
        cargandoGeneral: function (val) {
            if (val) {
                var etiqueta = this.$vs.loading({
                    text: 'Cargando...'
                });
                window.temp_cargando = etiqueta;
            } else
                this.$vs.loading.close()
        }
    }
};

export default mixin;
