(window.webpackJsonp=window.webpackJsonp||[]).push([[27],{cy9c:function(e,t,r){(e.exports=r("I1BE")(!1)).push([e.i,"#page-user-list .user-list-filters .vs__actions {\n  position: absolute;\n  top: 50%;\n}[dir] #page-user-list .user-list-filters .vs__actions {\n  -webkit-transform: translateY(-58%);\n          transform: translateY(-58%);\n}[dir=ltr] #page-user-list .user-list-filters .vs__actions {\n  right: 0;\n}[dir=rtl] #page-user-list .user-list-filters .vs__actions {\n  left: 0;\n}",""])},eEDV:function(e,t,r){"use strict";r.r(t);var a=r("o0o1"),i=r.n(a),s=r("QBvj"),n=(r("7emp"),r("Snq/")),o=r.n(n),l=r("RZZ2"),c=r("QvIH"),d=r("cB4Q"),p={name:"CeldaGestionarOrden",components:{ordenesGestionEdit:c.a,reservaEdit:d.default},data:function(){return{abrirPoput:!1,abrirPoputSegmentar:!1}},methods:{cerrarPoput:function(e){this.abrirPoput=!!e},eliminarReserva:function(e){}},watch:{abrirPoput:function(e){1==e&&this.$refs.componente_gestionar_orden.obtenerreservaId()},abrirPoputSegmentar:function(e){1==e&&this.$refs.componente_segmentar_reserva_asis.obtenerreservaId()}}},u=r("KHd+"),v=Object(u.a)(p,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"flex items-center"},[r("vx-tooltip",{attrs:{text:"Gestionar orden"}},[r("span",{staticClass:"text-inherit hover:text-primary cursor-pointer",on:{click:function(t){e.abrirPoput=!0}}},[r("span",{staticClass:"material-icons"},[e._v("new_releases")])])]),e._v(" "),2==e.params.data.idEstadoReserva&&e.params.data.nroOrden==e.params.data.nroOrdenPadre?r("vx-tooltip",{attrs:{text:"Segementar reserva"}},[r("span",{staticClass:"text-inherit hover:text-primary cursor-pointer",on:{click:function(t){e.abrirPoputSegmentar=!0}}},[r("span",{staticClass:"material-icons"},[e._v("list_alt")])])]):e._e(),e._v(" "),r("vs-popup",{attrs:{fullscreen:"",classContent:"popup-example",title:"Generación orden de trabajo ",active:e.abrirPoput},on:{"update:active":function(t){e.abrirPoput=t}}},[r("ordenes-gestion-edit",{ref:"componente_gestionar_orden",attrs:{"id-reserva":e.abrirPoput?e.params.data.idReserva:null},on:{cerrarPoput:e.cerrarPoput}})],1),e._v(" "),r("vs-popup",{attrs:{classContent:"popup-example",title:"Segmentar reserva "+e.params.data.nroOrden,active:e.abrirPoputSegmentar},on:{"update:active":function(t){e.abrirPoputSegmentar=t}}},[r("reserva-edit",{ref:"componente_segmentar_reserva_asis",attrs:{opcion:"asistente","id-reserva":e.params.data.idReserva}})],1)],1)}),[],!1,null,"79424206",null).exports,m=r("G91M");function f(e,t,r,a,i,s,n){try{var o=e[s](n),l=o.value}catch(e){return void r(e)}o.done?t(l):Promise.resolve(l).then(a,i)}var g,h,C={components:{AgGridVue:s.AgGridVue,vSelect:o.a,CeldaGestionarOrden:v,descargarExcel:m.a},data:function(){return{datos:[],tipoReservaFiltro:{},tipoReservaOpciones:[],clienteFiltro:{},clienteOpciones:[],estadoFiltro:{},estadoOpciones:[],asistenteFiltro:{},asistenteOpciones:[],empresasFiltro:{},empresasOpciones:[],searchQuery:"",gridApi:null,gridOptions:{},defaultColDef:{sortable:!0,resizable:!0,suppressMenu:!0},columnDefs:[{headerName:"No. Orden",field:"nroOrden",filter:!0,checkboxSelection:!1,headerCheckboxSelectionFilteredOnly:!0,headerCheckboxSelection:!1},{headerName:"Tema",field:"temaOrden",filter:!0},{headerName:"Empresa",field:"empresaOrden",filter:!0},{headerName:"Valor Total",field:"valorOrdenTotal",filter:!0,width:120},{headerName:"Días vencimiento",field:"Dias_vencimiento",filter:!0,width:120},{headerName:"Und",field:"unidadesOrden",filter:!0,width:120,cellClass:"text-center"},{headerName:"Unidad Medida",field:"undMedidaOrden",filter:!0,cellClass:"text-center"},{headerName:"Tipo",field:"tipo_Reserva",filter:!0},{headerName:"Cliente",field:"nombreCliente",filter:!0},{headerName:"Estado O.T",field:"nomestadoot",filter:!0},{headerName:"Gestionar",fiel:"gestionar",pinned:"right",width:100,cellClass:"text-center",cellRendererFramework:"CeldaGestionarOrden"}],components:{CeldaGestionarOrden:v}}},watch:{tipoReservaFiltro:function(e){this.setColumnFilter("tipo_Reserva",e.value)},clienteFiltro:function(e){this.setColumnFilter("nombreCliente",e.value)},estadoFiltro:function(e){this.setColumnFilter("nomestadoot",e.value)},empresasFiltro:function(e){this.setColumnFilter("empresaOrden",e.value)}},computed:{usersData:function(){return this.$store.state.userManagement.users},paginationPageSize:function(){return this.gridApi?this.gridApi.paginationGetPageSize():10},totalPages:function(){return this.gridApi?this.gridApi.paginationGetTotalPages():0},currentPage:{get:function(){return this.gridApi?this.gridApi.paginationGetCurrentPage()+1:1},set:function(e){this.gridApi.paginationGoToPage(e-1)}}},methods:{setColumnFilter:function(e,t){var r=null;"all"!==t&&(r={type:"equals",filter:t}),this.gridApi.getFilterInstance(e).setModel(r),this.gridApi.onFilterChanged()},resetColFilters:function(){this.gridApi.setFilterModel(null),this.gridApi.onFilterChanged(),this.roleFilter=this.statusFilter=this.isVerifiedFilter=this.departmentFilter={label:"All",value:"all"},this.$refs.filterCard.removeRefreshAnimation()},updateSearchQuery:function(e){this.gridApi.setQuickFilter(e)},listarOrdenes:function(){var e=this;this.cargandoGeneral=!0,axios.post("/ordenes/listarOrdenes",{idAsistente:"Diana Alvarado"}).then((function(t){e.datos=t.data.datos,e.tipoReservaOpciones=t.data.filtroTipoReserva,e.clienteOpciones=t.data.filtroClientes,e.estadoOpciones=t.data.filtroEstados,e.asistenteOpciones=t.data.filtroAsistente,e.empresasOpciones=t.data.filtroEmpresas,e.cargandoGeneral=!1}),(function(e){console.log(e)}))},descargarDatos:(g=i.a.mark((function e(){var t;return i.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return this.cargandoGeneral=!0,e.next=3,axios.post("/ordenes/descargarDatos");case 3:return t=e.sent,console.log(t),this.cargandoGeneral=!1,e.abrupt("return",t.data);case 7:case"end":return e.stop()}}),e,this)})),h=function(){var e=this,t=arguments;return new Promise((function(r,a){var i=g.apply(e,t);function s(e){f(i,r,a,s,n,"next",e)}function n(e){f(i,r,a,s,n,"throw",e)}s(void 0)}))},function(){return h.apply(this,arguments)})},mounted:function(){if(this.gridApi=this.gridOptions.api,this.$vs.rtl){var e=this.$refs.agGridTable.$el.querySelector(".ag-header-container");e.style.left="-"+String(Number(e.style.transform.slice(11,-3))+9)+"px"}this.$refs.filterCard.toggleContent()},created:function(){this,l.a.isRegistered||(this.$store.registerModule("userManagement",l.a),l.a.isRegistered=!0),this.$store.dispatch("userManagement/fetchUsers").catch((function(e){console.error(e)})),this.listarOrdenes()}},_=(r("vq5B"),Object(u.a)(C,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{attrs:{id:"page-user-list"}},[r("vx-card",{ref:"filterCard",staticClass:"user-list-filters mb-8",attrs:{title:"Filtros",actionButtons:""},on:{refresh:e.resetColFilters,remove:e.resetColFilters}},[r("div",{staticClass:"vx-row"},[r("div",{staticClass:"vx-col sm:12/12 md:w-12/12 lg:w-12/12"},[r("descargarExcel",{staticClass:"vs-component vs-button vs-button-border vs-button-primary",attrs:{fetch:e.descargarDatos,name:"datosReserva.xls"}},[e._v("\n                    Descargar datos\n                ")])],1)]),e._v(" "),r("div",{staticClass:"vx-row"},[r("div",{staticClass:"vx-col ms:w-12/12 md:w-4/12 lg:w-4/12 w-full"},[r("label",{staticClass:"text-sm opacity-75"},[e._v("Estado")]),e._v(" "),r("v-select",{attrs:{options:e.estadoOpciones,clearable:!1,dir:e.$vs.rtl?"rtl":"ltr"},model:{value:e.estadoFiltro,callback:function(t){e.estadoFiltro=t},expression:"estadoFiltro"}})],1),e._v(" "),r("div",{staticClass:"vx-col ms:w-12/12 md:w-4/12 lg:w-4/12 w-full"},[r("label",{staticClass:"text-sm opacity-75"},[e._v("Cliente")]),e._v(" "),r("v-select",{staticClass:"mb-4 sm:mb-0",attrs:{options:e.clienteOpciones,clearable:!1,dir:e.$vs.rtl?"rtl":"ltr"},model:{value:e.clienteFiltro,callback:function(t){e.clienteFiltro=t},expression:"clienteFiltro"}})],1),e._v(" "),r("div",{staticClass:"vx-col ms:w-12/12 md:w-4/12 lg:w-4/12 w-full"},[r("label",{staticClass:"text-sm opacity-75"},[e._v("Empresa")]),e._v(" "),r("v-select",{attrs:{options:e.empresasOpciones,clearable:!1,dir:e.$vs.rtl?"rtl":"ltr"},model:{value:e.empresasFiltro,callback:function(t){e.empresasFiltro=t},expression:"empresasFiltro"}})],1)])]),e._v(" "),r("div",{staticClass:"vx-card p-6"},[r("div",{staticClass:"flex flex-wrap items-center"},[r("div",{staticClass:"flex-grow"},[r("vs-dropdown",{staticClass:"cursor-pointer",attrs:{"vs-trigger-click":""}},[r("div",{staticClass:"p-4 border border-solid d-theme-border-grey-light rounded-full d-theme-dark-bg cursor-pointer flex items-center justify-between font-medium"},[r("span",{staticClass:"mr-2"},[e._v(e._s(e.currentPage*e.paginationPageSize-(e.paginationPageSize-1))+" - "+e._s(e.usersData.length-e.currentPage*e.paginationPageSize>0?e.currentPage*e.paginationPageSize:e.usersData.length)+" de "+e._s(e.usersData.length))]),e._v(" "),r("feather-icon",{attrs:{icon:"ChevronDownIcon",svgClasses:"h-4 w-4"}})],1),e._v(" "),r("vs-dropdown-menu",[r("vs-dropdown-item",{on:{click:function(t){return e.gridApi.paginationSetPageSize(10)}}},[r("span",[e._v("10")])]),e._v(" "),r("vs-dropdown-item",{on:{click:function(t){return e.gridApi.paginationSetPageSize(20)}}},[r("span",[e._v("20")])]),e._v(" "),r("vs-dropdown-item",{on:{click:function(t){return e.gridApi.paginationSetPageSize(25)}}},[r("span",[e._v("25")])]),e._v(" "),r("vs-dropdown-item",{on:{click:function(t){return e.gridApi.paginationSetPageSize(30)}}},[r("span",[e._v("30")])])],1)],1)],1),e._v(" "),r("vs-input",{staticClass:"sm:mr-4 mr-0 sm:w-auto w-full sm:order-normal order-3 sm:mt-0 mt-4",attrs:{placeholder:"Search..."},on:{input:e.updateSearchQuery},model:{value:e.searchQuery,callback:function(t){e.searchQuery=t},expression:"searchQuery"}})],1),e._v(" "),r("ag-grid-vue",{ref:"agGridTable",staticClass:"ag-theme-material w-100 my-4 ag-grid-table",staticStyle:{width:"100%",height:"400px"},attrs:{components:e.components,gridOptions:e.gridOptions,columnDefs:e.columnDefs,defaultColDef:e.defaultColDef,rowData:e.datos,rowSelection:"multiple",colResizeDefault:"shift",animateRows:!0,floatingFilter:!0,pagination:!0,paginationPageSize:e.paginationPageSize,suppressPaginationPanel:!0,enableRtl:e.$vs.rtl}}),e._v(" "),r("vs-pagination",{attrs:{total:e.totalPages,max:7},model:{value:e.currentPage,callback:function(t){e.currentPage=t},expression:"currentPage"}})],1)],1)}),[],!1,null,null,null));t.default=_.exports},j0Lt:function(e,t,r){var a=r("cy9c");"string"==typeof a&&(a=[[e.i,a,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};r("aET+")(a,i);a.locals&&(e.exports=a.locals)},vq5B:function(e,t,r){"use strict";var a=r("j0Lt");r.n(a).a}}]);