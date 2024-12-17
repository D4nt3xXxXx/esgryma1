<?php

namespace App\Http\Controllers\planificacion;

use App\Http\Controllers\Controller;
use App\modelos\ordenes;
use App\modelos\planificacion;
use App\modelos\reservas;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class planificacionController extends Controller
{
    function consultarPlanPrevio(Request $request)
    {
        $anio = $request->input("anio");
        $mes = $request->input("mes");
        $idPlan = planificacion::consultarIdPlan();
        $datosMeta = planificacion::consultarMetames($anio, $mes);
        $datosGestores = planificacion::gestoresDisponibles();
        $datosPlanPrevio = planificacion::planeadoPreviototal($anio, $mes);
        $datosNovedad = planificacion::novedades($anio, $mes);
        if (count($datosMeta) > 0)
            return response()->json([$datosMeta[0], $datosGestores, $datosPlanPrevio[0], $datosNovedad[0], empty($idPlan) ? 1 : $idPlan[0]->id]);
        else
            return response()->json("Lo sentimos, no hay una meta registrada en el sistema para el mes seleccionado!");
    }

    function consultarBasePlan()
    {
        $datos = planificacion::consultarBasePlan();
        $ordenar = array_group_by($datos, 'tipo_Reserva');
        $totalArl = 0;
        $totalComercial = 0;
        foreach ($ordenar as $index => $item) {
            foreach ($item as $index2 => $item2) {
                if ($index == "ARL")
                    $totalArl += $item2->valorOrdenTotal;
                else
                    $totalComercial += $item2->valorOrdenTotal;
            }
        }

        return response()->json([$totalArl, $totalComercial]);
    }

    function consultarPlanTipoArl(Request $request)
    {
        $totalArl = $request->input("totalArl");
        $totalComercial = $request->input("totalComercial");
        $prioridad = $request->input("prioridad");
        $disponibilidad = $request->input('disponibilidad');

        $datos = planificacion::consultarPlanTipoArl($totalArl, $totalComercial, $prioridad, $disponibilidad);

        $totalOrdenes = 0;
        $facturableArl = 0;
        $facturableComercial = 0;
        $totalHoras = 0;
        $totalOrdenes = count($datos);
        /*$temp_datos = array_group_by($datos, "tipo_Reserva");

        if (isset($temp_datos["ARL"])) {
            foreach ($temp_datos["ARL"] as $item) {
                $facturableArl += $item->valorOrdenTotal;
                $totalHoras += $item->unidadesOrden;
            }
        }
        if (isset($datos["COMERCIAL"])) {
            foreach ($datos["COMERCIAL"] as $item) {
                $facturableComercial += $item->valorOrdenTotal;
                $totalHoras += $item->unidadesOrden;
            }
        }*/
        foreach ($datos as $item) {
            if ($item->tipo_Reserva == 'ARL')
                $facturableArl += (double)$item->valorOrdenTotal;
            else
                $facturableComercial += (double)$item->valorOrdenTotal;
        }

        return response()->json([$totalOrdenes, $facturableArl, $facturableComercial, $totalHoras, $datos]);
    }

    function guardarPlan(Request $request)
    {
        $datos = $request->all();
        $datos["datosPlan"][0]["fechaCR"] = date('Y-m-d H:i');
        $datos["datosPlan"][0]["planea"] = Auth::user()->id;
        $res = planificacion::guardarPlan($datos["datosPlan"], $datos["reservas"]);
        return response()->json($res);
    }

    function guardarPlanManual(Request $request)
    {
        $datos = $request->input("datosPlan");
        $datosDetalle = array($request->input("datosDetalle"));
        $res = planificacion::guardarPlanManual($datos, $datosDetalle);
        return response()->json($res);
    }

    function consultarListaDetallePlan(Request $request)
    {
        $id = Auth::user()->id;
        $anio = $request->input('anio');
        $mes = $request->input("mes");
        $datos = planificacion::consultarPlanes($id, $anio, $mes);
        return response()->json($datos);
    }

    function consultarPlanes()
    {
        $id = Auth::user()->id;
        $datos = planificacion::consultarListaPlanes($id);
        return response()->json($datos);
    }

    function detallePlan(Request $request)
    {
        $idPlan = $request->input("id");
        $datos = planificacion::consultarDetallePlan($idPlan);
        return response()->json($datos);
    }

    function consultarReservasSinPlan(Request $request)
    {
        $mes = $request->input("mes");
        if (empty($mes))
            $mes = date("m");
        $datos = planificacion::consultarReservasSinPlan($mes);

        $tipoReserva = array_group_by($datos, 'tipo_Reserva');
        $clientes = array_group_by($datos, 'nombreCliente');

        $filtroTipoReserva = [];
        $filtroClientes = [];

        $todos = array("label" => "Todos", "value" => "all");
        array_push($filtroTipoReserva, $todos);
        array_push($filtroClientes, $todos);

        foreach ($tipoReserva as $index => $item) {
            array_push($filtroTipoReserva, array("label" => $index, "value" => $index));
        }
        foreach ($clientes as $index => $item) {
            array_push($filtroClientes, array("label" => $index, "value" => $index));
        }
        return response()->json([
            "datos" => $datos,
            "filtroTipoReserva" => $filtroTipoReserva,
            "filtroClientes" => $filtroClientes
        ]);
    }

    function planReservas(Request $request)
    {
        $anio = $request->input("anio");
        $mes = $request->input("mes");
        $datos = planificacion::planReservas($anio, $mes);

        return response()->json($datos);
    }
}
