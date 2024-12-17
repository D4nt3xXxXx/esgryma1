<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'cacheController@index');
Route::get('/borrarCache', 'cacheController@borrarCache');

Auth::routes(["register" => false]);

//NOTIFICACIONES
// Notifications
Route::post('notifications', 'notificaciones\notificacionesController@store');
Route::get('notifications', 'notificaciones\notificacionesController@index');
Route::patch('notifications/{id}/read', 'notificaciones\notificacionesController@markAsRead');
Route::post('notifications/mark-all-read', 'notificaciones\notificacionesController@markAllRead');
Route::post('notifications/{id}/dismiss', 'notificaciones\notificacionesController@dismiss');

// Push Subscriptions
Route::post('subscriptions', 'notificaciones\PushSuscriptionController@update');
Route::post('subscriptions/delete', 'notificaciones\PushSuscriptionController@destroy');

Route::get('/autenticacion', 'ApplicationController@autenticacion')->middleware('auth')->name('autenticacion');
// ROUTE PARA VUE.JS
Route::group(['prefix' => 'app'], function () {
    Route::group(['middleware' => ['auth']], function () {
        Route::get('/{any}', 'ApplicationController')->where('any', '.*');
    });
});
Route::group(['prefix' => 'consultas', 'as' => 'consultas.', 'middleware' => \App\Http\Middleware\autenticacion::class], function () {
    Route::post('/usuarios', 'HomeController@usuarios')->name('consultas.usuarios')->middleware('role:administrador');
    Route::post('/listarActividades', 'reserva\reservaController@listaActividades')->name('reservas.listaActividades');
    Route::post('/listarNovedades', 'consultasController@listarNovedades')->name('consultas.listarNovedades');
    Route::post('/consultarCostoPlaneado', 'consultasController@consultarCostoPlaneado')->name('consultas.consultarCostoPlaneado');
    Route::post('/consultarMetaMes', 'consultasController@consultarMetaMes')->name('consultas.consultarMetaMes');
    Route::post('/consultarAsistentes', 'consultasController@consultarAsistentes')->name('consultas.consultarAsistentes');
    Route::post('/configApp', 'consultasController@configApp')->name('consultas.configApp');
    Route::post('/consultarEstadoOT', 'consultasController@consultarEstadoOT')->name('consultas.consultarEstadoOT');

    Route::post('/consultarCategoria', 'consultasController@consultarCategoria')->name('consultas.consultarCategoria');
    Route::post('/consultarTema', 'consultasController@consultarTema')->name('consultas.consultarTema');
    Route::post('/consultarTipo', 'consultasController@consultarTipo')->name('consultas.consultaTipo');
    Route::post('/consultarGrupo', 'consultasController@consultarGrupo')->name('consultas.consultarGrupo');
    Route::post('/consultarColores', 'maestros\maestrosController@consultarColores')->name('consultas.consultarColores');
});
Route::group(['prefix' => 'reservas', 'as' => 'reservas.', 'middleware' => \App\Http\Middleware\autenticacion::class], function () {
    Route::post('/listaUndMedida', 'reserva\reservaController@listaUndMedida')->name('reservas.usuarios');
    Route::post('/listaTipoReserva', 'reserva\reservaController@listaTipoReserva')->name('reservas.listaTipoReserva');
    Route::post('/listaClientes', 'reserva\reservaController@listaClientes')->name('reservas.listaClientes');
    Route::post('/listaCliente', 'reserva\reservaController@listaCliente')->name('reservas.listaClientes');
    Route::post('/listaasistenteClienteId', 'reserva\reservaController@asistenteClienteId')->name('reservas.listaasistenteClienteId');
    Route::post('/listaGestores', 'reserva\reservaController@listaGestores')->name('reservas.listaGestores');
    Route::post('/listaGestoresDisponibles', 'reserva\reservaController@listaGestoresDisponibles')->name('reservas.listaGestoresDisponibles');
    Route::post('/listareservaId', 'reserva\reservaController@reservaId')->name('reservas.reservaId');
    Route::post('/Guardar', 'reserva\reservaController@guardarReserva')->name('reservas.Guardar');
    Route::post('/GuardarParticion', 'reserva\reservaController@guardarReservaParticion')->name('reservas.Guardar');
    Route::post('/listarReservas', 'reserva\reservaController@listarReservas')->name('reservas.listarReservas')->middleware('role:admin|asisadministrativo|liderprogramacion|coordprevencion');

    Route::get('/descargarArchivoReserva', 'reserva\reservaController@descargarArchivoReserva')->name('reservas.descargarArchivoReserva');
});

Route::group(['prefix' => 'ordenes', 'as' => 'ordenes.', 'middleware' => \App\Http\Middleware\autenticacion::class], function () {
    // Route::post('/listareservaId', 'reserva\reservaController@reservaId')->name('reservas.reservaId');
    Route::post('/listarOrdenes', 'ordenes\ordenesController@listarOrdenes')->name('ordenes.listarOrdenes')->middleware('role:admin|asisadministrativo|liderprogramacion|coordprevencion');
    Route::post('/listaActividades', 'ordenes\ordenesController@listaActividades')->name('ordenes.listaActividades')->middleware('role:admin|asisadministrativo|liderprogramacion|gestor');
    Route::post('/listaGestores', 'ordenes\ordenesController@listaGestores')->name('ordenes.listaGestores')->middleware('role:admin|asisadministrativo|liderprogramacion|gestor');
    Route::post('/listaCategoria', 'ordenes\ordenesController@listaCategoria')->name('ordenes.listaCategoria');
    Route::post('/listaSalon', 'ordenes\ordenesController@listaSalon')->name('ordenes.listaSalon');
    Route::post('/tipoActividad', 'ordenes\ordenesController@listarTipoActividad')->name('ordenes.tipoActividad');
    Route::post('/GuardarOrden', 'ordenes\ordenesController@guardarOrden')->name('ordenes.Guardar');
    Route::post('/listarTema', 'ordenes\ordenesController@listarTema')->name('ordenes.listarTema');
    Route::post('/programarOt', 'ordenes\ordenesController@programarOt')->name('ordenes.programarOt');
    Route::post('/listarParticiapantes', 'ordenes\ordenesController@listarParticiapantes')->name('ordenes.listarParticiapantes');
    Route::post('/guardarParticiapantes', 'ordenes\ordenesController@guardarParticiapantes')->name('ordenes.guardarParticiapantes');

    Route::post('/eliminarParticipante', 'ordenes\ordenesController@eliminarParticipante')->name('ordenes.eliminarParticipante');
    Route::post('/actualizarFacturado', 'ordenes\ordenesController@actualizarFacturado')->name('ordenes.actualizarFacturado');
    Route::post('/listarBitacoras', 'ordenes\ordenesController@listarBitacoras')->name('ordenes.listarBitacoras');
    Route::post('/guardarBitacora', 'ordenes\ordenesController@guardarBitacora')->name('ordenes.guardarBitacora');

    Route::post('/cancelarOT', 'ordenes\ordenesController@cancelarOT')->name('ordenes.cancelarOT')->middleware('role:admin|asisadministrativo');
    Route::post('/actualizarOtCancelada', 'ordenes\ordenesController@actualizarOtCancelada')->name('ordenes.actualizarOtCancelada')->middleware('role:admin|liderprogramacion');
    Route::post('/eliminarReserva', 'ordenes\ordenesController@eliminarReserva')->name('ordenes.eliminarReserva')->middleware('role:admin|liderprogramacion');
    Route::post('/descargarDatos', 'ordenes\ordenesController@descargarDatos')->name('ordenes.descargarDatos')->middleware('role:admin|asisadministrativo');
});
Route::group(['prefix' => 'planificacion', 'as' => 'planificacion.', 'middleware' => \App\Http\Middleware\autenticacion::class], function () {

    Route::post('/consultarPlanPrevio', 'planificacion\planificacionController@consultarPlanPrevio')->name('ordenes.consultarPlanPrevio')->middleware('role:admin|liderprogramacion');
    Route::post('/consultarBasePlan', 'planificacion\planificacionController@consultarBasePlan')->name('ordenes.consultarBasePlan')->middleware('role:admin|liderprogramacion');
    Route::post('/consultarPlanTipoArl', 'planificacion\planificacionController@consultarPlanTipoArl')->name('ordenes.consultarPlanTipoArl')->middleware('role:admin|liderprogramacion');
    Route::post('/guardarPlan', 'planificacion\planificacionController@guardarPlan')->name('ordenes.guardarPlan')->middleware('role:admin|liderprogramacion');
    Route::post('/guardarPlanManual', 'planificacion\planificacionController@guardarPlanManual')->name('ordenes.guardarPlanManual')->middleware('role:admin|liderprogramacion');
    Route::post('/consultarListaDetallePlan', 'planificacion\planificacionController@consultarListaDetallePlan')->name('ordenes.consultarListaDetallePlan')->middleware('role:admin|liderprogramacion');
    Route::post('/consultarListaPlanes', 'planificacion\planificacionController@consultarPlanes')->name('ordenes.consultarListaPlanes')->middleware('role:admin|liderprogramacion');
    Route::post('/consultarDetallePlan', 'planificacion\planificacionController@detallePlan')->name('ordenes.consultarDetallePlan')->middleware('role:admin|liderprogramacion');
    Route::post('/consultarReservasSinPlan', 'planificacion\planificacionController@consultarReservasSinPlan')->name('ordenes.consultarReservasSinPlan')->middleware('role:admin|liderprogramacion');
    Route::post('/planReservas', 'planificacion\planificacionController@planReservas')->name('ordenes.planReservas')->middleware('role:admin|liderprogramacion');
});
Route::group(['prefix' => 'gestor', 'as' => 'gestor.', 'middleware' => \App\Http\Middleware\autenticacion::class], function () {

    Route::post('/listarOT', 'gestor\gestorController@listarOT')->name('ordenes.listarOT')->middleware('role:admin|gestor');
    Route::post('/listarEventosAgendaGestor', 'gestor\gestorController@listarEventosAgendaGestor')->name('ordenes.listarEventosAgendaGestor')->middleware('role:admin|gestor|asisadministrativo');
    Route::post('/listarEventosAgenda', 'gestor\gestorController@listarEventosAgenda')->name('ordenes.listarEventosAgenda')->middleware('role:admin|gestor|asisadministrativo');
    Route::post('/filtroEventosAgenda', 'gestor\gestorController@filtroEventosAgenda')->name('ordenes.filtroEventosAgenda')->middleware('role:admin|gestor|asisadministrativo');
    Route::post('/actualizarEncuesta', 'gestor\gestorController@actualizarEncuesta')->name('ordenes.actualizarEncuesta')->middleware('role:admin|gestor');
    Route::post('/actualizarInforme', 'gestor\gestorController@actualizarInforme')->name('ordenes.actualizarInforme')->middleware('role:admin|gestor');
    Route::post('/guardarOrdenRealizada', 'gestor\gestorController@guardarOrdenRealizada')->name('ordenes.guardarOrdenRealizada')->middleware('role:admin|gestor|coordprevencion|asisadministrativo');
    Route::post('/actualizarEstados', 'gestor\gestorController@actualizarEstados')->name('ordenes.actualizarEstados')->middleware('role:admin|gestor|coordprevencion|asisadministrativo');
});
Route::group(['prefix' => 'salones', 'as' => 'salones.', 'middleware' => \App\Http\Middleware\autenticacion::class], function () {
    Route::post('/listarEventosSalones', 'salones\salonesController@listarEventosSalones')->name('ordenes.listarEventosSalones')->middleware('role:admin|asisadministrativo');
});
Route::group(['prefix' => 'procesos', 'as' => 'procesos.', 'middleware' => \App\Http\Middleware\autenticacion::class], function () {
    Route::post('/novedad.guardar', 'procesos\procesosController@guardarNovedad')->name('novedad.guardarNovedad')->middleware('role:admin|liderprogramacion|asisadministrativo');
    Route::post('/novedad.editar', 'procesos\procesosController@editarNovedad')->name('novedad.editarNovedad')->middleware('role:admin|liderprogramacion|asisadministrativo');
    Route::post('/novedad.listarNovedades', 'procesos\procesosController@listarNovedades')->name('novedad.listarNovedades')->middleware('role:admin|liderprogramacion|asisadministrativo');
    Route::post('/novedad.eliminar', 'procesos\procesosController@eliminarNovedad')->name('novedad.eliminar')->middleware('role:admin|liderprogramacion|asisadministrativo');
    Route::post('/novedad.listarOtRev', 'procesos\procesosController@listarOtRev')->name('novedad.listarOtRev')->middleware('role:admin|coordprevencion');

    Route::post('/facturarOT', 'procesos\procesosController@facturarOT')->name('novedad.facturarOT')->middleware('role:admin|asisadministrativo');

    Route::post('/listarEventosSalones', 'procesos\procesosController@listarEventosSalones')->name('novedad.listarEventosSalones');
    Route::post('/consultarNovedad', 'procesos\procesosController@consultarNovedad')->name('novedad.consultarNovedad');

    Route::post('/consultarMetaFacturado', 'procesos\procesosController@consultarMetaFacturado')->name('procesos.consultarNovedad');
});
Route::group(['prefix' => 'maestros', 'as' => 'maestros.', 'middleware' => \App\Http\Middleware\autenticacion::class], function () {
    Route::post('/consultarAsistentes', 'maestros\maestrosController@consultarAsistentes')->name('ordenes.consultarAsistentes')->middleware('role:admin|liderprogramacion');
    Route::post('/guardarCliente', 'maestros\maestrosController@guardarCliente')->name('ordenes.guardarCliente')->middleware('role:admin|liderprogramacion');
    Route::post('/eliminarCliente', 'maestros\maestrosController@eliminarCliente')->name('ordenes.eliminarCliente')->middleware('role:admin|liderprogramacion');
    Route::post('/consultarActividades', 'maestros\maestrosController@consultarActividades')->name('ordenes.consultarActividades')->middleware('role:admin|liderprogramacion');
    Route::post('/guardarActividad', 'maestros\maestrosController@guardarActividad')->name('ordenes.guardarActividad')->middleware('role:admin|liderprogramacion');
    Route::post('/consultarMatriz', 'maestros\maestrosController@consultarMatriz')->name('ordenes.consultarMatriz')->middleware('role:admin|liderprogramacion');
    Route::post('/guardarGestorMatriz', 'maestros\maestrosController@guardarGestorMatriz')->name('ordenes.guardarGestorMatriz')->middleware('role:admin|liderprogramacion');
    Route::post('/eliminarGestorMatriz', 'maestros\maestrosController@eliminarGestorMatriz')->name('ordenes.eliminarGestorMatriz')->middleware('role:admin|liderprogramacion');

    Route::post('/consultarUsuarios', 'maestros\maestrosController@consultarUsuarios')->name('ordenes.consultarUsuarios')->middleware('role:admin|liderprogramacion');
    Route::post('/eliminarUsuario', 'maestros\maestrosController@eliminarUsuario')->name('ordenes.eliminarUsuario')->middleware('role:admin|liderprogramacion');
    Route::post('/informacionUsuario', 'maestros\maestrosController@informacionUsuario')->name('ordenes.informacionUsuario')->middleware('role:admin|liderprogramacion');
    Route::post('/guardarDatosUsuario', 'maestros\maestrosController@guardarDatosUsuario')->name('ordenes.guardarDatosUsuario')->middleware('role:admin|liderprogramacion');
    Route::post('/actualizarImagen', 'maestros\maestrosController@actualizarImagen')->name('ordenes.actualizarImagen')->middleware('role:admin|liderprogramacion');
    Route::post('/eliminarFoto', 'maestros\maestrosController@eliminarFoto')->name('ordenes.eliminarFoto')->middleware('role:admin|liderprogramacion');
    Route::post('/actualizarClave', 'maestros\maestrosController@actualizarClave')->name('ordenes.actualizarClave')->middleware('role:admin|liderprogramacion');
    Route::post('/listaPermisos', 'maestros\maestrosController@listaPermisos')->name('ordenes.listaPermisos')->middleware('role:admin|liderprogramacion');
    Route::post('/listaRoles', 'maestros\maestrosController@listaRoles')->name('ordenes.listaRoles')->middleware('role:admin|liderprogramacion');
    Route::post('/permisosUsuario', 'maestros\maestrosController@permisosUsuario')->name('ordenes.permisosUsuario')->middleware('role:admin|liderprogramacion');
    Route::post('/rolesUsuario', 'maestros\maestrosController@rolesUsuario')->name('ordenes.rolesUsuario')->middleware('role:admin|liderprogramacion');
    Route::post('/guardarUsuario', 'maestros\maestrosController@guardarUsuario')->name('ordenes.guardarUsuario')->middleware('role:admin|liderprogramacion');

    Route::post('/consultarTipoNovedad', 'maestros\maestrosController@consultarTipoNovedad')->name('ordenes.consultarTipoNovedad')->middleware('role:admin|liderprogramacion');
    Route::post('/guardarTipoNovedad', 'maestros\maestrosController@guardarTipoNovedad')->name('ordenes.guardarTipoNovedad')->middleware('role:admin|liderprogramacion');
    Route::post('/eliminarTipoNovedad', 'maestros\maestrosController@eliminarTipoNovedad')->name('ordenes.eliminarTipoNovedad')->middleware('role:admin|liderprogramacion');

    Route::post('/consultarTipoActividad', 'maestros\maestrosController@consultarTipoActividad')->name('ordenes.consultarTipoActividad')->middleware('role:admin|liderprogramacion');
    Route::post('/guardarTipoActividad', 'maestros\maestrosController@guardarTipoActividad')->name('ordenes.guardarTipoActividad')->middleware('role:admin|liderprogramacion');
    Route::post('/eliminarTipoActividad', 'maestros\maestrosController@eliminarTipoActividad')->name('ordenes.eliminarTipoActividad')->middleware('role:admin|liderprogramacion');

    Route::post('/consultarCategoria', 'maestros\maestrosController@consultarCategoria')->name('ordenes.consultarCategoria')->middleware('role:admin|liderprogramacion');
    Route::post('/guardarCategoria', 'maestros\maestrosController@guardarCategoria')->name('ordenes.guardarCategoria')->middleware('role:admin|liderprogramacion');
    Route::post('/eliminarCategoria', 'maestros\maestrosController@eliminarCategoria')->name('ordenes.eliminarCategoria')->middleware('role:admin|liderprogramacion');

    Route::post('/consultarGrupo', 'maestros\maestrosController@consultarGrupo')->name('ordenes.consultarGrupo')->middleware('role:admin|liderprogramacion');
    Route::post('/guardarGrupo', 'maestros\maestrosController@guardarGrupo')->name('ordenes.guardarGrupo')->middleware('role:admin|liderprogramacion');
    Route::post('/eliminarGrupo', 'maestros\maestrosController@eliminarGrupo')->name('ordenes.eliminarGrupo')->middleware('role:admin|liderprogramacion');

    Route::post('/consultarTema', 'maestros\maestrosController@consultarTema')->name('ordenes.consultarTema')->middleware('role:admin|liderprogramacion');
    Route::post('/guardarTema', 'maestros\maestrosController@guardarTema')->name('ordenes.guardarTema')->middleware('role:admin|liderprogramacion');
    Route::post('/eliminarTema', 'maestros\maestrosController@eliminarTema')->name('ordenes.eliminarTema')->middleware('role:admin|liderprogramacion');

    Route::post('/consultarListaMetaMes', 'maestros\maestrosController@consultarListaMetaMes')->name('ordenes.consultarListaMetaMes')->middleware('role:admin|liderprogramacion');
    Route::post('/guardarMetaMes', 'maestros\maestrosController@guardarMetaMes')->name('ordenes.guardarMetaMes')->middleware('role:admin|liderprogramacion');

});
Route::group(['prefix' => 'reportes', 'as' => 'reportes.', 'middleware' => \App\Http\Middleware\autenticacion::class], function () {
    Route::post('/consultarReporte', 'consultasController@consultarReporte')->name('reportes.consultarReporte')->middleware('role:admin|liderprogramacion|asisadministrativo|gestor|liderservicio');
    Route::post('/consultarTablaReportes', 'consultasController@consultarTablaReportes')->name('reportes.consultarTablaReportes')->middleware('role:admin|liderprogramacion|asisadministrativo|gestor|liderservicio');
});
Route::group(['prefix' => 'documentos', 'as' => 'documentos.', 'middleware' => \App\Http\Middleware\autenticacion::class], function () {
    Route::post('/guardarDocumento', 'docController@guardarDocumento')->name('documentos.guardarDocumento');
    Route::post('/consultarDocumentos', 'docController@consultarDocumentos')->name('documentos.consultarDocumentos');
    Route::post('/verDcto', 'docController@verDcto')->name('documentos.verDcto');
    Route::get('/descargarDcto', 'docController@descargarDcto')->name('documentos.descargarDcto');
});
/*Route::get('/orden-servicio', function () {
    return view('os');
});*/
Route::post('import', 'OrdenServicio\OrdenServicioController@import')->name('orden.servicio');
Route::post('import-os', 'OrdenServicio\OrdenServicioController@store')->name('orden.servicio');
Route::post('handbook-os', 'OrdenServicio\OrdenServicioController@storeHandbook')->name('orden.servicio.manual');
