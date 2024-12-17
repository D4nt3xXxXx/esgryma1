<?php

namespace App\OrdenServicio;

use Illuminate\Database\Eloquent\Model;

class OrdenServicio extends Model
{
    //
    protected $table = 'reserva';

    protected $fillable = [
        'nroOrden','nroOrdenPadre','temaOrden','empresaOrden','valorOrdenUnit','valorOrdenTotal','obsOrden','fecIniOrden'
        ,'fecFinOrden','fecSubida','unidadesOrden','undMedidaOrden','idUserSube','idTipo','idUserAsigna','idUserAsignadoa','idCliente'
        ,'estadoReserva','gestorSolicitado','idEstadoReserva','unidadesOrdenTotal',"participantes"
    ];
}
