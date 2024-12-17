<?php
/**
 * Created by PhpStorm.
 * User: breynerperez
 * Date: 16/02/2020
 * Time: 9:50 PM
 */

namespace App\modelos;


use Illuminate\Support\Facades\DB;

class gestor
{
    static function listarOT($id, $idFiltro = null, $opcion = null)
    {
        $select = "SELECT * FROM view_ot_detalle vot ";
        $where = "";
        if (empty($idFiltro) && empty($opcion) || $opcion == 'todo')
            $where = "where vot.idGestor=$id order by vot.fechaActividad asc;";
        else {
            if ($opcion == 'tipoActividad')
                $where = "where vot.idGestor=$id and (vot.idtipoactividad=$idFiltro) order by vot.fechaActividad asc;";
            else if ($opcion == 'estadoOT')
                $where = "where vot.idGestor=$id and (vot.idestadoot=$idFiltro)";
            else if ($opcion == 'buscar') {
                $idFiltro = strtolower($idFiltro);
                $where = "where vot.idGestor=$id and (lower(vot.tipoActividad) like '%$idFiltro%' or lower(vot.nroOrdenPadre) like '%$idFiltro%')";
            }
        }
        return DB::select($select . $where);
    }

    static function listarEventosAgendaGestor($id)
    {
        return DB::select("select * from view_agenda_gestor_novedades where idGestor=$id");
    }

    static function listarEventosAgenda($idGestores)
    {
        if (empty($idGestores))
            return DB::select("select * from view_agenda_gestor_novedades");
        else
            return DB::table("view_agenda_gestor_novedades")->whereIn("gestorNombre", $idGestores)->get()->toArray();
        return DB::select(/*"SELECT 'OT' AS tipo,
       ot.idOT AS id,
       ot.idReserva,
       ot.actividad AS titulo,
       '' AS novedad,
       ot.fechaActividad AS fechaInicio,
       ot.fechaFinActividad AS fechaFinal,
       '' AS color,
       ot.nroOrdenPadre,
       ot.nroorden,
       ot.idtipoactividad,
       ot.tipoactividad,
       ot.nomEstado,
       ot.idestadoot,
       ot.empresaOrden,
       ot.tiempoEjecutar,
       ot.idGestor,
       ot.Gestor,
       ot.idSalon,
       ot.nombreSalon,
       ot.fechaInforme,
       ot.direccion,
       ot.urlInforme,
       ot.telContacto,
       ot.nombreContacto,
       ot.observaciones,
       ot.ciudad,
       ot.idestadoreserva,
       ot.llamada,
       ot.llegada,
       ot.idAsistente
FROM view_ot_detalle ot
UNION
(SELECT 'NOVEDAD'
           AS tipo,
        n.idNovedad
           AS id,
        ''
           AS idReserva,
        concat(n.tipoNovedad,
               CASE WHEN isnull(n.observacion) THEN '' ELSE ' - ' END,
               ifnull(n.observacion, ''))
           AS titulo,
        n.tipoNovedad
           AS novedad,
        n.fechaInicio,
        n.fechaFin,
        n.colorNovedad
           AS color,
        ''
           AS nroOrdenPadre,
        ''
           AS nroorden,
        ''
           AS idtipoactividad,
        ''
           AS tipoactividad,
        ''
           AS nomEstado,
        ''
           AS idestadoot,
        ''
           AS empresaOrden,
        ''
           AS tiempoEjecutar,
        n.idGestor
           AS idGestor,
        n.Gestor
           AS Gestor,
        ''
           AS idSalon,
        ''
           AS nombreSalon,
        ''
           AS fechaInforme,
        ''
           AS direccion,
        ''
           AS urlInforme,
        ''
           AS telContacto,
        ''
           AS nombreContacto,
        ''
           AS observaciones,
        ''
           AS ciudad,
        ''
           AS idestadoreserva,
        ''
           AS llamada,
        ''
           AS llegada,
        ''
           AS idAsistente
 FROM view_novedades n group by n.idNovedad,n.fechaInicio,n.fechaFin,n.tipoNovedad )"*/ "select * from view_agenda_gestor_novedades where gestorNombre in ($idGestores)");
    }

    static function actualizarEncuesta($idReserva, $encuesta)
    {
        return DB::table('ot')->where("idReserva", $idReserva)->update(["encuestaOK" => $encuesta == true ? 1 : 0]);
    }

    static function actualizarInforme($idReserva, $informe)
    {
        return DB::table('ot')->where("idReserva", $idReserva)
            ->update(["informeOK" => $informe == true ? 1 : 0]);
    }

    static function guardarOrdenRealizada($estadoReserva, $estadoOt, $idReserva, $idOT)
    {
        DB::beginTransaction();
        try {
            DB::table("reserva")->where(['idReserva' => $idReserva])
                ->update(["idEstadoReserva" => $estadoReserva]);
            DB::table("ot")->where(['idOT' => $idOT])->update(["idEstadoOT" => $estadoOt]);
            DB::commit();
            return 'ok';
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    static function actualizarEstados($idOT, $valor, $opcion)
    {
        $fecha = ($valor == true ? date('Y-m-d H:i') : null);
        $datoActualizar = [];
        if ($opcion == "llamada") {
            $datoActualizar = ["llamada" => $fecha];
        } else {
            $datoActualizar = ["llegada" => $fecha];
        }
        DB::table('ot')->where(['idOT' => $idOT])->update($datoActualizar);
        return empty($fecha) ? '' : $fecha;
    }
}
