<?php

namespace App\Http\Controllers;

use App\modelos\consultas;
use App\modelos\planificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class consultasController extends Controller
{
    //
    function listarNovedades()
    {
        $datos = consultas::listarNovedades();
        return response()->json($datos);
    }

    function consultarCostoPlaneado(Request $request)
    {
        $anio = date("Y");
        $mes = $request->input("mes");
        $datosPlanPrevio = planificacion::planeadoPreviototal($anio, $mes)[0]->planPrevio;
        return response()->json($datosPlanPrevio);
    }

    function consultarMetaMes(Request $request)
    {
        $anio = $request->input("anio");
        $mes = $request->input("mes");
        $datosMeta = planificacion::consultarMetames($anio, $mes);
        return response()->json(count($datosMeta) > 0 ? $datosMeta[0]->meta : 'Lo sentimos, no hay una meta para el mes seleccionado, registre la meta para el mes');
    }

    function consultarAsistentes($id = null)
    {
        if (empty($id))
            return DB::select("select u.id,u.name as text from users u
join  role_user r on r.user_id=u.id
where r.role_id=2");
        else
            return DB::select("select u.id,u.name as text from users u
join  role_user r on r.user_id=u.id
where r.role_id=2 and u.id=$id");

    }

    function configApp(Request $request)
    {
        $atributo = $request->input("atributo");
        $datos = consultas::configApp($atributo);
        return response()->json($datos);
    }

    function consultarEstadoOT()
    {
        $datos = consultas::consultarEstadoOT();
        return response()->json($datos);
    }

    function consultarCategoria()
    {
        return response()->json(consultas::consultarCategoria());
    }

    function consultarTema()
    {
        return response()->json(consultas::consultarTema());
    }

    function consultarTipo()
    {
        return response()->json(consultas::consultarTipo());
    }

    function consultarGrupo()
    {
        return response()->json(consultas::consultarGrupo());
    }

    function consultarReporte(Request $request)
    {
        $datos = $request->all();
        $consulta = $datos["consulta"];

        $fechaInicio = date('Y-m-d', strtotime($datos["fechaInicio"]));
        $fechaFin = date('Y-m-d', strtotime($datos["fechaFin"]));;
        $datos = consultas::consultarReporte($consulta, $fechaInicio, $fechaFin);

        return response()->json($datos);
    }

    function consultarTablaReportes(Request $request)
    {
        $datos = $request->all();
        $categoria = $datos["categoria"];
        $subcategoria = $datos["subcategoria"];
        $fechaDesde = null;
        $fechaHasta = null;

        $a_date = date("Y-m-d");
        $date = new \DateTime($a_date);
        $date->modify('last day of this month');
        $fechaHasta = $date->format('Y-m-d');

        $a_date = date("Y-m-d");
        $date = new \DateTime($a_date);
        $date->modify('first day of this month');
        $fechaDesde = $date->format('Y-m-d');

        $tempDatos = consultas::consultarTablaReportes($categoria, $subcategoria);
        $reportes = [];
        foreach ($tempDatos as $dato) {
            array_push($reportes, array("id" => $dato->id, "text" => $dato->nombreRep, "idCategoria" => $dato->idCategoria, "idSubCategoria" => $dato->idSubCategoria, "data" => $dato));
        }
        return response()->json(array("datos" => $tempDatos, "reportes" => $reportes));
    }
}
