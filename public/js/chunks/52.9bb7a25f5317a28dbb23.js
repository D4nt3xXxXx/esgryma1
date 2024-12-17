(window.webpackJsonp=window.webpackJsonp||[]).push([[52],{"1lMR":function(e,a,t){"use strict";t.r(a);var s=t("wd/R"),o=t.n(s),i=t("ExVU"),n={name:"nuevaNovedad",data:function(){return{fechaActual:i.DateTime.local(),novedad:{idTipoNovedad:null,idGestor:[],horas:null,fechaInicio:null,fechaFin:null,autoriza:null,observacion:null},horasAdicional:null,opcion:"",listaTipoNovedad:[],listaGestores:[],listaHoras:[]}},created:function(){this.opcion=this.$route.query.opcion,this.listarGestores(),this.listarNovedades()},methods:{guardarNovedad:function(){var e=this;this.loading=!0,axios.post("/procesos/novedad.guardar",this.novedad).then((function(a){e.loading=!1,e.mostrarNotificacion("Registro exitoso","Se ha registrado la novedad con exito!","success"),e.novedad.fechaFin=e.novedad.fechaInicio=e.novedad.observacion=e.novedad.autoriza=e.novedad.idTipoNovedad=null,e.novedad.idGestor=[],e.novedad.horas=null,e.horasAdicional=null}))},listarGestores:function(){var e=this;axios.post("/reservas/listaGestores").then((function(a){e.listaGestores=a.data}))},listarNovedades:function(){var e=this;axios.post("/consultas/listarNovedades").then((function(a){e.listaTipoNovedad=a.data}))},calcularHoras:function(){if(null!=this.novedad.fechaInicio&&""!=this.novedad.fechaInicio&&null!=this.novedad.fechaFin&&""!=this.novedad.fechaFin){var e=o()(this.novedad.fechaInicio).locale("America/Bogota"),a=o()(this.novedad.fechaFin).locale("America/Bogota").diff(e,"hours");this.listaHoras=a>0?a:[]}},sumarHorasFecha:function(){if(null!=this.novedad.fechaInicio&&""!=this.novedad.fechaInicio1){var e=o()(this.novedad.fechaInicio).locale("America/Bogota").add(this.horasAdicional,"hours");this.novedad.fechaFin=i.DateTime.fromISO(e.format(),{zone:"America/Bogota"}).toISO()}},sumar_horas:function(){var e=document.getElementsByName("fecha")[0].value,a=document.getElementsByName("horas")[0].value;(e=new Date(e)).setHours(e.getHours()+a);var t=(e=e.toISOString()).split("T"),s=t[1].split(".");s=t[1].split(":");e=(t=t[0])+" "+s[0]+":"+s[1],document.getElementsByName("log")[0].value=e},validarFechas:function(){return null!=this.novedad.fechaInicio&&""!=this.novedad.fechaInicio&&null!=this.novedad.fechaFin&&""!=this.novedad.fechaFin?($("input[id=gestores]").prop("disabled",!1),this.gestoresDisponibles(),!1):($("input[id=gestores]").prop("disabled",!0),!0)},gestoresDisponibles:function(){var e=this;this.cargandoGeneral=!0,axios.post("/reservas/listaGestoresDisponibles",this.novedad).then((function(a){e.listaGestores=a.data,e.cargandoGeneral=!1}))}},watch:{"novedad.fechaInicio":{handler:function(e,a){this.calcularHoras(),this.validarFechas()},deep:!0},"novedad.fechaFin":{handler:function(e,a){this.calcularHoras(),this.validarFechas()},deep:!0}}},l=t("KHd+"),d=Object(l.a)(n,(function(){var e=this,a=e.$createElement,t=e._self._c||a;return t("ValidationObserver",{ref:"formNovedad",attrs:{tag:"form"},scopedSlots:e._u([{key:"default",fn:function(a){var s=a.handleSubmit;return[t("form",{on:{submit:function(a){return a.preventDefault(),s(e.guardarNovedad)}}},[t("vs-card",{attrs:{title:"Nueva novedad"}},[t("div",{staticClass:"vx-row"},[t("div",{staticClass:"vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 mb-2"},[t("span",[e._v("Tipo novedad")]),e._v(" "),t("validation-provider",{attrs:{name:"TIPO NOVEDAD",rules:"required|numeric"},scopedSlots:e._u([{key:"default",fn:function(a){var s=a.errors;return[e.isRole("admin|liderprogramacion")?t("vs-select",{staticClass:"selectExample w-full",attrs:{autocomplete:""},model:{value:e.novedad.idTipoNovedad,callback:function(a){e.$set(e.novedad,"idTipoNovedad",a)},expression:"novedad.idTipoNovedad"}},[t("vs-select-item",{key:"",attrs:{value:"",text:"---seleccione---"}}),e._v(" "),e._l(e.listaTipoNovedad,(function(e,a){return t("vs-select-item",{key:a,staticClass:"disabledx",attrs:{value:e.id,text:e.text}})}))],2):e.isRole("admin|asisadministrativo")?t("vs-select",{staticClass:"selectExample w-full",attrs:{autocomplete:""},model:{value:e.novedad.idTipoNovedad,callback:function(a){e.$set(e.novedad,"idTipoNovedad",a)},expression:"novedad.idTipoNovedad"}},[t("vs-select-item",{key:"",attrs:{value:"",text:"---seleccione---"}}),e._v(" "),e._l(e.listaTipoNovedad,(function(a,s){return"1,2"==a.rolvalidar?t("vs-select-item",{key:s,staticClass:"disabledx",attrs:{value:a.id,text:a.text}}):e._e()}))],2):e._e(),e._v(" "),t("span",{staticClass:"text-danger"},[e._v(e._s(s[0]))])]}}],null,!0)})],1),e._v(" "),t("div",{staticClass:"vx-col sm:w-12/12 md:w-8/12 lg:w-8/12 mb-2"},[t("div",{staticClass:"vx-row flex align-bottom justify-space-between "},[t("div",{staticClass:"vx-col sm:w-11/12 md:w-11/12 lg:w-11/12"},[t("span",[e._v("Gestor")]),e._v(" "),t("validation-provider",{attrs:{name:"GESTOR",rules:"required|numeric"},scopedSlots:e._u([{key:"default",fn:function(a){var s=a.errors;return[t("vs-select",{staticClass:"gestores",attrs:{placeholder:"Seleccione el(los) gestor(es)",multiple:"",autocomplete:"",width:"100%",id:"gestores",disabled:e.validarFechas},model:{value:e.novedad.idGestor,callback:function(a){e.$set(e.novedad,"idGestor",a)},expression:"novedad.idGestor"}},e._l(e.listaGestores,(function(e,a){return t("vs-select-item",{key:a,attrs:{value:e.id,text:e.text}})})),1),e._v(" "),t("span",{staticClass:"text-danger"},[e._v(e._s(s[0]))])]}}],null,!0)})],1),e._v(" "),t("vs-col",{attrs:{"vs-sm":"1","vs-md":"1","vs-lg":"1","vs-align":"flex-end","vs-type":"flex","vs-justify":"space-between"}},[t("vs-button",{attrs:{color:"dark",type:"line",icon:"event_note"}})],1)],1)])]),e._v(" "),t("div",{staticClass:"vx-row"},[t("div",{staticClass:"vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 mb-2"},[t("strong",[e._v("Fecha Inicio")]),e._v(" "),t("validation-provider",{attrs:{name:"FECHA INICIO",rules:"required"},scopedSlots:e._u([{key:"default",fn:function(a){var s=a.errors;return[t("datetime",{attrs:{type:"datetime","input-class":"vs-inputx vs-input--input normal","value-zone":"America/Bogota",zone:"America/Bogota",format:"yyyy-MM-dd H:m",auto:""},model:{value:e.novedad.fechaInicio,callback:function(a){e.$set(e.novedad,"fechaInicio",a)},expression:"novedad.fechaInicio"}}),e._v(" "),t("span",{staticClass:"text-danger"},[e._v(e._s(s[0]))])]}}],null,!0)})],1),e._v(" "),t("div",{staticClass:"vx-col sm:w-12/12 md:w-4/12 lg:w-4/12  mb-2"},[t("div",{staticClass:"vs-row"},[t("div",{staticClass:"vs-col sm:w-2/12 md:w-2/12 lg:w-2/12"},[t("strong",[e._v("Horas")]),e._v(" "),t("vs-select",{staticClass:"selectExample w-full",attrs:{autocomplete:""},on:{input:e.sumarHorasFecha},model:{value:e.horasAdicional,callback:function(a){e.horasAdicional=a},expression:"horasAdicional"}},e._l(40,(function(e,a){return t("vs-select-item",{key:a,attrs:{value:e,text:e}})})),1)],1),e._v(" "),t("div",{staticClass:"vs-col sm:w-10/12 md:w-10/12 lg:w-10/12"},[t("strong",[e._v("Fecha Fin")]),e._v(" "),t("validation-provider",{attrs:{name:"FECHA FIN",rules:"required"},scopedSlots:e._u([{key:"default",fn:function(a){var s=a.errors;return[t("datetime",{attrs:{type:"datetime","input-class":"vs-inputx vs-input--input normal","value-zone":"America/Bogota",zone:"America/Bogota",format:"yyyy-MM-dd H:m","min-datetime":e.novedad.fechaInicio,auto:""},model:{value:e.novedad.fechaFin,callback:function(a){e.$set(e.novedad,"fechaFin",a)},expression:"novedad.fechaFin"}}),e._v(" "),t("span",{staticClass:"text-danger"},[e._v(e._s(s[0]))])]}}],null,!0)})],1)])]),e._v(" "),t("div",{staticClass:"vx-col sm:w-12/12 md:w-4/12 lg:w-4/12 mb-2"},[t("span",[e._v("Horas disponibles (-)")]),e._v(" "),t("validation-provider",{attrs:{name:"HORAS DISPONIBLES",rules:"required"},scopedSlots:e._u([{key:"default",fn:function(a){var s=a.errors;return[t("vs-select",{staticClass:"selectExample w-full",attrs:{autocomplete:""},model:{value:e.novedad.horas,callback:function(a){e.$set(e.novedad,"horas",a)},expression:"novedad.horas"}},[t("vs-select-item",{key:"",attrs:{value:"",text:"---seleccione---"}}),e._v(" "),e._l(e.listaHoras,(function(e,a){return t("vs-select-item",{key:a,attrs:{value:e,text:e}})}))],2),e._v(" "),t("span",{staticClass:"text-danger"},[e._v(e._s(s[0]))])]}}],null,!0)})],1),e._v(" "),t("div",{staticClass:"vx-col sm:w-12/12 md:w-4/12 lg:w-4/12  mb-2"},[t("vs-input",{staticClass:"w-full",attrs:{label:"Autoriza"},model:{value:e.novedad.autoriza,callback:function(a){e.$set(e.novedad,"autoriza",a)},expression:"novedad.autoriza"}})],1),e._v(" "),t("div",{staticClass:"vx-col sm:w-12/12 md:w-8/12 lg:w-8/12  mb-2"},[t("vs-input",{staticClass:"w-full",attrs:{label:"Observación"},model:{value:e.novedad.observacion,callback:function(a){e.$set(e.novedad,"observacion",a)},expression:"novedad.observacion"}})],1)]),e._v(" "),t("div",{staticClass:"vx-row flex justify-content-center justify-center"},[t("div",{staticClass:"vx-col sm:w-12/12 md:w-12/12 lg:w-12/12  mb-2 center text-center"},[t("boton",{attrs:{texto_cargando:"Guardando novedad...",type:"submit",texto:"Guardar novedad",cargando:e.loading}})],1)])])],1)]}}])})}),[],!1,null,"80c784ac",null);a.default=d.exports}}]);