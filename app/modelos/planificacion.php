<?php
/**
 * Created by PhpStorm.
 * User: breynerperez
 * Date: 14/02/2020
 * Time: 11:22 PM
 */

namespace App\modelos;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class planificacion
{
    static function consultarIdPlan()
    {
        return DB::select("select (1+ifnull(idPlan,0)) id from plan order by idPlan desc limit 1;");
    }

    static function consultarMetames($anio, $mes)
    {
        return DB::select("select ifnull(meta,0) as meta from metames where anio=$anio and mes=$mes");
    }

    static function gestoresDisponibles()
    {
        return DB::select("select u.id,name as nombre from users u join role_user ru on u.id=ru.user_id where ru.role_id=3 and u.activo=1;");
    }

    static function planeadoPreviototal($anio, $mes)
    {
        return DB::select("select ifnull(sum(p.monto),0) as planPrevio from plan p where p.planAnio=$anio and p.planMes=$mes;");
    }

    static function novedades($anio, $mes)
    {
        return DB::select("select ifnull(sum(n.horas),0) as horas from novedad n where YEAR(n.fechaInicio)=$anio and MONTH(n.fechaInicio)=$mes;");
    }

    static function consultarBasePlan()
    {
        return DB::select("select * from view_base_plan");
    }

    static function consultarPlanTipoArl($totalArl, $totalComercial, $prioridad = null, $disponibilidad = null)
    {
        if ($prioridad != "Disponibilidad") {
            $limitArl = DB::select("SELECT fun_indice_Reserva_arl($totalArl) as id_arl;");
            if (count($limitArl) > 0)
                $limitArl = $limitArl[0]->id_arl;
            else
                $limitArl = 1;
            $limitComercial = DB::select("SELECT fun_indice_Reserva_comercial($totalComercial) as id_com;");
            if (count($limitComercial) > 0)
                $limitComercial = $limitComercial[0]->id_com;
            else
                $limitComercial = 1;

            $select = "(SELECT * FROM view_base_plan where tipo_Reserva='COMERCIAL' limit $limitComercial) union
(SELECT * FROM view_base_plan where tipo_Reserva='ARL' limit $limitArl);";
            $datos = DB::select($select);
            return $datos;
        } else {
            $limitDisponibilidad = DB::select("SELECT fun_indice_Reserva_horas($disponibilidad) as id_arl;")[0]->id_arl;
            $datosDisponibilidad = DB::select("SELECT * FROM view_base_plan limit $limitDisponibilidad;");
            return $datosDisponibilidad;
        }
    }

    static function guardarPlan($datosPlan, $datosPlanDetalle)
    {
        DB::beginTransaction();
        $insertDetalle = [];
        $idReservas = [];
        try {
            $id = DB::table('plan')->insertGetId($datosPlan[0]);
            foreach ($datosPlanDetalle as $item) {
                array_push($idReservas, $item["idReserva"]);
                array_push($insertDetalle, ['idReserva' => $item["idReserva"], 'idPlan' => $id, 'valor' => $item["valorOrdenTotal"], 'horas' => $item["unidadesOrden"]]);
            }
            $idAsistente = array_group_by($datosPlanDetalle, "idAsistente");
            foreach ($idAsistente as $index => $item) {
                $updateEstado = [];
                foreach ($item as $item2) {
                    array_push($updateEstado, $item2["idReserva"]);
                }
                DB::table("reserva")->whereIn("idReserva", $updateEstado)->update(["idUserAsignadoa" => $index, "idUserAsigna" => Auth::user()->id]);
            }
            DB::table("detalleplan")->insert($insertDetalle);
            DB::table("reserva")->whereIn("idReserva", $idReservas)->update(["idEstadoReserva" => 2]);
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

    static function guardarPlanManual($datos, $datosPlanDetalle)
    {
        $datos["criterio"] = 'DINERO';
        $datos["horasDisponibles"] = 0;
        $datos["fechaCR"] = date("Y-m-d H:i");
        $datos["planea"] = Auth::user()->id;
        $datos["porComercial"] = 0;
        $datos["porARL"] = 0;
        $datos["origen"] = "MANUAL";
        $idUserAsignadoa = $datos["idUserAsignadoa"];

        DB::beginTransaction();
        $insertDetalle = [];
        $idReservas = [];
        unset($datos["idUserAsignadoa"]);
        try {
            $idPlan = DB::table("plan")->insertGetId($datos);

            foreach ($datosPlanDetalle as $item) {
                array_push($idReservas, $item["idReserva"]);
                array_push($insertDetalle, ['idReserva' => $item["idReserva"], 'idPlan' => $idPlan, 'valor' => $item["valorOrdenTotal"], 'horas' => $item["unidadesOrden"]]);
            }
            $idAsistente = array_group_by($datosPlanDetalle, "IdAsistenteAdmin");
            foreach ($idAsistente as $index => $item) {
                $updateEstado = [];
                foreach ($item as $item2) {
                    array_push($updateEstado, $item2["idReserva"]);
                }
                DB::table("reserva")->whereIn("idReserva", $updateEstado)->update(["idUserAsignadoa" => $idUserAsignadoa, "idUserAsigna" => Auth::user()->id]);
            }
            DB::table("detalleplan")->insert($insertDetalle);
            DB::table("reserva")->whereIn("idReserva", $idReservas)->update(["idEstadoReserva" => 2]);
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

    static function consultarPlanes($id, $anio, $mes)
    {
        return DB::select("select sub.*,ifnull(dp.horas,0) as horasDisponibles from (SELECT p.idPLan as id,
       p.planMes,
       p.planAnio,
       p.criterio,
       p.monto,
       p.horasDisponibles as horasDisponiblesPlan,
       p.metaMes,
       p.fechaCR,
       p.planea,
       p.porComercial,
       p.porARL,
       p.origen
FROM plan p where p.planAnio=$anio and p.planMes=$mes order by p.planMes asc) sub
left join detalleplan dp on dp.idPlan=sub.id
group by sub.id");
    }

    static function consultarListaPlanes($id)
    {
        return DB::select("SELECT p.idPLan as id,
       p.planMes,
       p.planAnio,
       p.criterio,
       sum(p.monto) as monto,
       sum(p.horasDisponibles) as horasDisponibles,
       p.metaMes,
       p.fechaCR,
       p.planea,
       p.porComercial,
       p.porARL,
       p.origen
FROM plan p
group by p.planAnio,p.planMes
order by p.planMes asc;");
    }

    static function consultarDetallePlan($id)
    {
        return DB::select("SELECT dp.idDetallePlan AS id,
       r.nroOrden,
       r.nroOrdenPadre,
       r.obsOrden,
       e.nomEstado AS estadoReserva,
       r.valorOrdenUnit as valorunitario,
       dp.valor,
       dp.horas
FROM detalleplan dp
     JOIN reserva r ON r.idReserva = dp.idReserva
     JOIN estados e ON e.idEstado = r.idEstadoReserva
WHERE dp.idPlan=$id;");
    }

    static function consultarReservasSinPlan($mes)
    {
        return DB::select("select * from view_reservas r
join view_base_plan b on b.idReserva=r.idReserva
where month(fecIniOrden)=$mes");
    }

    static function planReservas($anio,$mes)
    {
        $datos = DB::select("select * from view_info_reservas where planAnio=$anio and planMes=$mes");
        return $datos;
    }
}
