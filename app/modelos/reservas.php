<?php
/**
 * Created by PhpStorm.
 * User: breynerperez
 * Date: 2/02/2020
 * Time: 4:59 PM
 */

namespace App\modelos;


use Illuminate\Support\Facades\DB;

class reservas
{
    static function listaTipoUnidades()
    {
        return DB::select("select idUnidMedida as id,unidadmedida as text from undmedida");
    }

    static function listaTipoReserva()
    {
        return DB::select("select idTipo as id,Tipo_Reserva as text from tipo_reserva");
    }

    static function listaCliente()
    {
        return DB::select("select idCliente as id,concat(nit,' ',nombreCliente) as text from cliente;");
    }

    static function listaClientes()
    {
        return DB::select("SELECT c.idCliente AS id,
       c.nombreCliente AS nombre,
       c.nit,
       c.tipoCliente AS tipo,
       c.asesor,
       c.nombreContacto,
       c.telefonoContacto,
       asis.idAsistenteAdmin as idAsistente,
       asis.id as idAsistentexcliente
FROM cliente c
left join asisadminxcliente asis on c.idCliente=asis.idCliente;");
    }

    static function asistenteClienteId($id)
    {
        return DB::select("select u.id,u.name as text from asisadminxcliente asis
        join users u on u.id=asis.idAsistenteAdmin
        where asis.idCliente=$id");
    }

    static function reservaId($idReserva)
    {
        return DB::select("SELECT r.idReserva,
       r.nroOrden,
       r.nroOrdenPadre,
       r.temaOrden,
       r.empresaOrden,
       r.valorOrdenUnit,
       r.valorOrdenTotal,
       r.obsOrden,
       r.fecIniOrden,
       r.fecFinOrden,
       r.fecSubida,
       r.unidadesOrden,
       r.unidadesOrdenTotal,
       r.undMedidaOrden,
       r.idUserSube,
       r.idTipo,
       r.idUserAsigna,
       r.idUserAsignadoa,
       r.idCliente,
       c.nombreCliente,
       r.gestorSolicitado,
       r.idEstadoReserva,
       r.created_at,
       r.updated_at,
       r.participantes,
       o.idOT,
       o.idGestor,
       o.idActividad,
       o.fechaActividad,
       o.asesorARL,
       o.tipoActividad,
       o.tiempoEjecutar,
       o.fechaInforme,
       o.direccion,
       o.coordDireccion,
       o.nombreContacto,
       o.estadoOT,
       o.observaciones,
       o.facturada,
       o.urlInforme,
       o.idEstadoOT,
       o.idSalon,
       o.created_at,
       o.updated_at,
       o.telContacto,
       o.ciudad,
       o.encuestaOK,
       o.informeOK,
       es.nomEstado,
       o.idcurso,
       o.fechaFinActividad,
       r.escurso
FROM reserva r
LEFT JOIN ot o ON o.idReserva = r.idReserva
left join estados es on es.idEstado=o.idEstadoOT
left join cliente c on c.idCliente=r.idCliente
where r.idReserva=$idReserva");
    }

    static function listaGestores()
    {
        return DB::select("SELECT u.id,u.name as text
FROM users u JOIN role_user r ON u.id = r.user_id
WHERE r.role_id = 3 and u.activo=1;");
    }

    static function listaGestoresDisponibles($fechaInicio, $fechaFin)
    {
        return DB::select("SELECT users.id, users.name as text
 FROM (esgryma_dev.users users
 LEFT OUTER JOIN
 (SELECT novedad.idGestor
 FROM esgryma_dev.novedad novedad
 WHERE
 (('$fechaInicio' BETWEEN fechaInicio and fechaFin) and ('$fechaFin' BETWEEN fechaInicio and fechaFin)) OR
 ((fechaInicio>='$fechaInicio' and fechaInicio<'$fechaFin')and fechaFin>='$fechaFin') OR
 (fechaInicio<='$fechaInicio' and (fechaFin>'$fechaInicio' and fechaFin<='$fechaFin'))
 GROUP BY novedad.idGestor) Subquery
 ON (users.id = Subquery.idGestor))
 LEFT OUTER JOIN esgryma_dev.role_user role_user
 ON (role_user.user_id = users.id)
 WHERE (users.activo = 1)
 AND (Subquery.idGestor IS NULL)
 AND (role_user.role_id = 3)
 order by users.name asc");
    }

    static function existeReserva($numOrden)
    {
        return DB::select("select nroOrden from reserva where nroOrden='$numOrden'");
    }

    static function guardarReserva($datos, $opcion, $idReserva = null)
    {
        if ($opcion == "nueva")
            return DB::table("reserva")->insert($datos);
        else
            return DB::table('reserva')->where('idReserva', $idReserva)->update($datos);
    }

    static function listarReservas()
    {
        return DB::select("SELECT * FROM view_reservas;");
    }

    static function guardarSegmentacion($datos, $idReserva, $opcion = null)
    {
        DB::beginTransaction();

        try {
            // se valida cuando la segmentacion viene desde el asistente y no del lider de programación
            if ($opcion == "asistente") {
                DB::table("detalleplan")->where("idReserva", $idReserva)->delete();
                DB::table("ot")->where("idReserva", $idReserva)->delete();
            }
            $eli = DB::table("reserva")->where("idReserva", $idReserva)->delete();
            $insert = DB::table("reserva")->insert($datos);

            DB::commit();
            return $insert;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }
}
