<?php
/**
 * Created by PhpStorm.
 * User: breynerperez
 * Date: 20/02/2020
 * Time: 10:58 PM
 */

namespace App\modelos;


use Illuminate\Support\Facades\DB;

class procesos
{
    static function guardarNovedad($datos)
    {
        $datos["fechaInicio"] = date('Y-m-d H:i', strtotime($datos["fechaInicio"]));
        $datos["fechaFin"] = date('Y-m-d H:i', strtotime($datos["fechaFin"]));
        $datos["fechaRegistro"] = date('Y-m-d H:i');
        $gestores = $datos["idGestor"];
        $temp_insert = [];
        foreach ($gestores as $item) {
            $datos["idGestor"] = $item;
            array_push($temp_insert, $datos);
        }
        return DB::table("novedad")->insert($temp_insert);
    }

    static function editarNovedad($datos)
    {
        $idNovedad = $datos["idNovedad"];
        $idgestor = $datos["idGestor"];
        $fechaInicio = date("Y-m-d H:i", strtotime($datos["fechaInicio"]));
        $fechaFin = date("Y-m-d H:i", strtotime($datos["fechaFin"]));
        $horas = $datos["horas"];
        $observacion = $datos["observacion"];
        unset($datos["idNovedad"]);
        $datosInsert = array("idGestor" => $idgestor, "fechaInicio" => $fechaInicio, "fechaFin" => $fechaFin, "horas" => $horas, "observacion" => $observacion);
        DB::beginTransaction();
        try {
            DB::table("novedad")->where("idNovedad", $idNovedad)->update($datosInsert);
            DB::commit();
            return 'ok';
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        } catch (\Throwable $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }

    static function listaNovedades($gestor, $fechaInicio, $fechaFinal)
    {
        $datos = DB::table("view_novedades");
        if (!empty($gestor))
            $datos->whereIn("idGestor", $gestor);
        if (!empty($fechaInicio) && !empty($fechaFinal)) {
            $datos->whereBetween(DB::raw("cast(fechaInicio as date)"), [$fechaInicio, $fechaFinal]);
            $datos->whereBetween(DB::raw("cast(fechaFin as date)"), [$fechaInicio, $fechaFinal]);
        }
        $datos->orderBy("fechaInicio", "desc");
        return $datos->get();
    }

    static function eliminarNovedad($id)
    {
        return DB::table("novedad")->where("idNovedad", $id)->delete();
    }

    static function listarOtRev()
    {
        return DB::select("select * from view_ot_detalle where idestadoot=7;");
    }

    static function consultarNovedad($idNovedad)
    {
        $datos = DB::select("select n.idNovedad as id,n.fechaInicio,n.fechaFin,n.horas,n.observacion,
group_concat(u.name) as gestor,u.id as gestorId,tn.tipoNovedad as novedad,tn.idTipoNovedad novedadId
from novedad n
join users u on u.id=n.idGestor
join tipo_novedad tn on tn.idTipoNovedad=n.idTipoNovedad
where n.idNovedad=$idNovedad");
        return $datos;
    }

    static function consultarMetaFacturado($aniomesDesde, $anioMeshasta)
    {
        $datos = DB::select("select * from rep_db_mpfg where aniomes>=$aniomesDesde and aniomes<=$anioMeshasta order by anio,mes asc");
        return $datos;
    }

    static function consultarReservasTipo($anio, $mes)
    {
        $datos = DB::select("select * from rep_db_reservas_tipo where anio=$anio and mes=$mes");

        return $datos;
    }

    static function consultarTotalCliente($anio, $mes)
    {
        $datos = DB::select("select * from rep_db_reservas_cliente where anio=$anio and mes=$mes");

        return $datos;
    }
}
