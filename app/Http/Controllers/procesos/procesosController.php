<?php

namespace App\Http\Controllers\procesos;

use App\Http\Controllers\Controller;
use App\modelos\procesos;
use App\modelos\salones;
use Colors\RandomColor;
use Illuminate\Http\Request;

class procesosController extends Controller
{
    //
    function guardarNovedad(Request $request)
    {
        $datos = $request->all();
        $res = procesos::guardarNovedad($datos);
        return response()->json($res);
    }

    function editarNovedad(Request $request)
    {
        $datos = $request->all();
        $res = procesos::editarNovedad($datos);
        return response()->json($res);
    }

    function listarNovedades(Request $request)
    {
        $gestor = $request->input("gestor");
        $fechaInicio = $request->input("fechaInicio");
        $fechaFin = $request->input("fechaFinal");
        $fecha = new \DateTime();
        if (empty($fechaInicio)) {
            $fecha->modify('first day of this month');

            $fechaInicio = $fecha->format('Y-m-d');
        } else {
            $fechaInicio = date("Y-m-d", strtotime($fechaInicio));
        }
        if (empty($fechaFin)) {
            $fecha->modify('last day of this month');
            $fechaFin = $fecha->format('Y-m-d');
        } else {
            $fechaFin = date("Y-m-d", strtotime($fechaFin));
        }
        $datos = procesos::listaNovedades($gestor, $fechaInicio, $fechaFin);
        $tempGestores = [];
        $gestor = collect($datos)->groupBy('Gestor');
        foreach ($gestor as $index => $item) {
            array_push($tempGestores, array("id" => $item[0]->idGestor, "text" => $index));
        }
        return response()->json(array("datos" => $datos, "gestores" => $tempGestores, "fechaInicio" => $fechaInicio, "fechaFinal" => $fechaFin));
    }

    function eliminarNovedad(Request $request)
    {
        $id = $request->input("id");
        return response()->json(procesos::eliminarNovedad($id));
    }

    function listarOtRev()
    {
        $datos = procesos::listarOtRev();
        $gestor = collect($datos)->groupBy("idGestor");
        $tipoActividad = collect($datos)->groupBy("idtipoactividad");
        $filtroGestor = [];
        $filtroTipoActividad = [];
        array_push($filtroGestor, array("value" => 'all', "label" => 'Todos'));
        array_push($filtroTipoActividad, array("value" => 'all', "label" => 'Todos'));
        foreach ($gestor as $index => $item) {
            array_push($filtroGestor, array("value" => $item[0]->Gestor, "label" => $item[0]->Gestor));
        }
        foreach ($tipoActividad as $index => $item) {
            array_push($filtroTipoActividad, array("value" => $item[0]->tipoactividad, "label" => $item[0]->tipoactividad));
        }
        return response()->json(array("datos" => $datos, "filtroGestores" => $filtroGestor, "filtroTipoActividad" => $filtroTipoActividad));
    }

    function facturarOT(Request $request)
    {
        $idReserva = $request->input("idReserva");
        $idOT = $request->input("idOT");
        $estadoReserva = $request->input("estadoReserva");
        $estadoOT = $request->input("estadoOT");

    }

    function listarEventosSalones(Request $request)
    {
        $idSalon = $request->input('idSalon');

        $datos = salones::listarEventosSalones($idSalon);
        $temp_datos = [];
        $agruparTipo = array_group_by($datos, "idSalon");

        /*foreach ($agruparTipo as $item) {
            foreach ($item as $item2) {
                array_push($temp_datos, array("id" => $item2->idReserva, "title" => $item2->actividad, "start" => $item2->fechaActividad, "end" => $item2->fechaFinActividad));
            }
        }*/
        $salonessLista = array_group_by($datos, 'nombreSalon');
        $salonesTemp = [];
        $colores = RandomColor::many(count($salonessLista), array('format' => 'rgbCss', 'luminosity' => 'random'));
        $cont = 0;
        foreach ($salonessLista as $index => $item) {
            array_push($salonesTemp, array("id" => trim(str_replace(".", '', str_replace(' ', '', $index))), "text" => $index, "color" => $colores[$cont]));
            foreach ($item as $item2) {
                array_push($temp_datos, array("tipo" => 'OT', "id" => str_replace(".", '', $item2->idReserva), "title" => $item2->actividad, "start" => $item2->fechaActividad, "end" => $item2->fechaFinActividad, "color" => $colores[$cont], "classNames" => trim(str_replace(".", '', str_replace(' ', '', $index)))));
            }
            $cont++;
        }

        return response()->json([$temp_datos, $salonesTemp]);
        //return response()->json($temp_datos);
    }

    function consultarNovedad(Request $request)
    {
        $idNovedad = $request->input("idNovedad");
        $datos = procesos::consultarNovedad($idNovedad);
        return response()->json($datos);
    }

    function consultarMetaFacturado(Request $request)
    {
        $opcion = $request->input("opcion");
        $fechaActual = date("Y-m-d");
        $fechaHasta = date("d-m-Y", strtotime($fechaActual . "- 12 month"));
        $aniomesDesde = date("Ym", strtotime($fechaHasta));
        $aniomesHasta = date("Ym", strtotime($fechaActual));
        $anio = date("Y");
        $mes = (int)date("m");
        if ($opcion == "grafico1") {
            $datos = procesos::consultarMetaFacturado($aniomesDesde, $aniomesHasta);
            $labels = [];
            $series = [];
            array_push($series, array("name" => "Total Planeado", "type" => "column", "data" => []));
            array_push($series, array("name" => "Total programado", "type" => "column", "data" => []));
            array_push($series, array("name" => "Total facturado", "type" => "column", "data" => []));
            array_push($series, array("name" => "Meta", "type" => "line", "data" => []));
            foreach ($datos as $dato) {
                array_push($labels, $dato->anio . '-' . substr($dato->nom_mes, 0, 3));
                array_push($series[0]["data"], $dato->Total_planeado);
                array_push($series[1]["data"], $dato->Total_prog);
                array_push($series[2]["data"], $dato->Total_fact);
                array_push($series[3]["data"], $dato->meta);
            }
            return response()->json([$datos, $labels, $series]);
        } else if ($opcion == "grafico2") {
            $datos = procesos::consultarMetaFacturado($aniomesHasta, $aniomesHasta);
            return response()->json($datos);
        } else if ($opcion == "grafico3") {
            $datos = procesos::consultarReservasTipo($anio, $mes);
            $total = collect($datos)->sum('Total');
            $tempDatos = array("labels" => [], "series" => [], "total" => $total);

            foreach ($datos as $dato) {
                array_push($tempDatos["labels"], $dato->nomestadoot);
                array_push($tempDatos["series"], $dato->Total);
            }
            return response()->json($tempDatos);
        } else if ($opcion == "grafico4") {
            $datos = procesos::consultarTotalCliente($anio, $mes);
            $tempDatos = array("labels" => [], "series" => []);
            foreach ($datos as $dato) {
                array_push($tempDatos["labels"], $dato->nombreCliente);
                array_push($tempDatos["series"], array("value" => $dato->Total, "name" => $dato->nombreCliente));
            }
            return response()->json($tempDatos);
        }
    }
}
