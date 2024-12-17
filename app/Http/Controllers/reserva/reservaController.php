<?php

namespace App\Http\Controllers\reserva;

use App\Http\Controllers\Controller;
use App\modelos\ordenes;
use App\modelos\reservas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;

class reservaController extends Controller
{
    function listaUndMedida()
    {
        $datos = reservas::listaTipoUnidades();
        return response()->json($datos);
    }

    function listaTipoReserva()
    {
        $datos = reservas::listaTipoReserva();
        return response()->json($datos);
    }

    function listaCliente()
    {
        $datos = reservas::listaClientes();
        return response()->json($datos);
    }

    function listaClientes()
    {
        $datos = reservas::listaCliente();
        return response()->json($datos);
    }

    function asistenteClienteId(Request $request)
    {
        $idCliente = $request->input("idCliente");
        $datos = reservas::asistenteClienteId($idCliente);
        return response()->json($datos);
    }

    function reservaId(Request $request)
    {
        $idReserva = $request->input("idReserva");
        $datos = reservas::reservaId($idReserva);
        return response()->json($datos);
    }

    function listaGestores()
    {
        $datos = reservas::listaGestores();
        return response()->json($datos);
    }

    function listaGestoresDisponibles(Request $request)
    {
        $fechaInicio = date("Y-m-d H:i", strtotime($request->input("fechaInicio")));
        $fechaFin = date("Y-m-d H:i", strtotime($request->input("fechaFin")));
        $datos = reservas::listaGestoresDisponibles($fechaInicio, $fechaFin);
        return response()->json($datos);
    }


    function guardarReserva(Request $request)
    {
        $datos = $request->all();
        $nroOrden = $datos["nroOrden"];
        $fecIniOrden = date("Y-m-d", strtotime($datos["fecIniOrden"]));
        $fecFinOrden = date("Y-m-d", strtotime($datos["fecFinOrden"]));
        $fecha = date("Y-m-d H:i");
        $opcion = $datos["opcion"];
        $idReserva = isset($datos["idReserva"]) ? $datos["idReserva"] : null;

        $temp_datos = array(
            'nroOrden' => $datos["nroOrden"],
            'nroOrdenPadre' => $datos["nroOrden"],
            'temaOrden' => $datos["temaOrden"],
            'empresaOrden' => $datos["empresaOrden"],
            'valorOrdenUnit' => $datos["valorOrdenUnit"],
            'valorOrdenTotal' => $datos["valorTotalOrdenTotal"],
            'obsOrden' => $datos["obsOrden"],
            'fecIniOrden' => $fecIniOrden,
            'fecFinOrden' => $fecFinOrden,
            'fecSubida' => $fecha,
            'unidadesOrden' => $datos["unidadesOrden"],
            "unidadesOrdenTotal" => $datos["unidadesOrden"],
            'undMedidaOrden' => $datos["undMedidaOrden"],
            'idUserSube' => Auth::user()->id,
            'idTipo' => $datos["idTipo"],
            'idCliente' => $datos["idCliente"],
            'gestorSolicitado' => $datos["gestorSolicitado"],
            'idEstadoReserva' => 1,
            'created_at' => $fecha,
            'updated_at' => $fecha,
            /*"iduserAsigna" => Auth::user()->id,*/
            //"idUserAsignadoa" => $datos["asignadoa"],
            "participantes" => $datos["participantes"],
            "escurso" => $datos["escurso"]
        );

        $existenroOrden = reservas::existeReserva($nroOrden);
        if (count($existenroOrden) == 0 && $opcion == "nueva") {
            if (empty($existenroOrden)) {
                reservas::guardarReserva($temp_datos, $opcion);
                return response()->json("ok");
            } else {
                return response()->json("Lo sentimos la solictud ingresada ya existe.");
            }
        } else {
            $temp_datos = array(
                'temaOrden' => $datos["temaOrden"],
                'empresaOrden' => $datos["empresaOrden"],
                'valorOrdenUnit' => $datos["valorOrdenUnit"],
                'valorOrdenTotal' => $datos["valorTotalOrdenTotal"],
                'obsOrden' => $datos["obsOrden"],
                'fecIniOrden' => $fecIniOrden,
                'fecFinOrden' => $fecFinOrden,
                'fecSubida' => $fecha,
                'unidadesOrden' => $datos["unidadesOrden"],
                "unidadesOrdenTotal" => $datos["unidadesOrden"],
                'undMedidaOrden' => $datos["undMedidaOrden"],
                'idUserSube' => Auth::user()->id,
                'idTipo' => $datos["idTipo"],
                'idCliente' => $datos["idCliente"],
                'gestorSolicitado' => $datos["gestorSolicitado"],
                'idEstadoReserva' => 1,
                'created_at' => $fecha,
                'updated_at' => $fecha,
                /*"iduserAsigna" => Auth::user()->id,*/
                //"idUserAsignadoa" => $datos["asignadoa"],
                "participantes" => $datos["participantes"],
                "escurso" => $datos["escurso"]
            );
            reservas::guardarReserva($temp_datos, $opcion, $idReserva);
            return response()->json("ok");
        }
    }

    function guardarReservaParticion(Request $request)
    {
        $datos_res = $request->input("reserva");
        $idReserva = $request->input("idReserva");
        $totalHoras = $request->input("totalHoras");
        $fecha = date("Y-m-d H:i");
        $opcion = $request->input("opcion");
        $temp_array = [];
        foreach ($datos_res as $datos) {
            $temp_datos = array(
                'nroOrden' => $datos["nroOrden"],
                'nroOrdenPadre' => $datos["nroOrdenPadre"],
                'temaOrden' => $datos["temaOrden"],
                'empresaOrden' => $datos["empresaOrden"],
                'valorOrdenUnit' => $datos["valorOrdenUnit"],
                'valorOrdenTotal' => $datos["valorOrdenTotal"],
                'obsOrden' => $datos["obsOrden"],
                'fecIniOrden' => $datos["fecIniOrden"],
                'fecFinOrden' => $datos["fecFinOrden"],
                'fecSubida' => $datos["fecSubida"],
                'unidadesOrden' => $datos["unidadesOrden"],
                "unidadesOrdenTotal" => $totalHoras,
                'undMedidaOrden' => 'HORAS',
                'idUserSube' => $datos["idUserSube"],
                'idTipo' => $datos["idTipo"],
                'idCliente' => $datos["idCliente"],
                'gestorSolicitado' => $datos["gestorSolicitado"],
                'idEstadoReserva' => $opcion == 'asistente' ? $datos["idEstadoReserva"] : 1,
                'created_at' => $fecha,
                'updated_at' => $fecha,
                "idUserAsigna" => $datos["idUserAsigna"],
                "idUserAsignadoa" => $datos["idUserAsignadoa"],
                "participantes" => $datos["participantes"]
            );
            array_push($temp_array, $temp_datos);
        }
        reservas::guardarSegmentacion($temp_array, $idReserva, $opcion);
        return response()->json('ok');
    }

    function listarReservas()
    {
        $datos = reservas::listarReservas();
        $tipoReserva = array_group_by($datos, 'tipo_Reserva');
        $clientes = array_group_by($datos, 'nombreCliente');
        $estados = array_group_by($datos, 'nomEstado');
        $asistentes = array_group_by($datos, 'AsistenteAdmin');
        $filtroTipoReserva = [];
        $filtroClientes = [];
        $filtroEstados = [];
        $filtroAsistentes = [];

        $todos = array("label" => "Todos", "value" => "all");
        array_push($filtroTipoReserva, $todos);
        array_push($filtroClientes, $todos);
        array_push($filtroEstados, $todos);
        array_push($filtroAsistentes, $todos);

        foreach ($tipoReserva as $index => $item) {
            array_push($filtroTipoReserva, array("label" => $index, "value" => $index));
        }
        foreach ($clientes as $index => $item) {
            array_push($filtroClientes, array("label" => $index, "value" => $index));
        }
        foreach ($estados as $index => $item) {
            array_push($filtroEstados, array("label" => $index, "value" => $index));
        }
        foreach ($asistentes as $index => $item) {
            array_push($filtroAsistentes, array("label" => $index, "value" => $index));
        }
        return response()->json([
            "datos" => $datos,
            "filtroTipoReserva" => $filtroTipoReserva,
            "filtroClientes" => $filtroClientes,
            "filtroEstados" => $filtroEstados,
            "filtroAsistente" => $filtroAsistentes
        ]);
    }

    function descargarArchivoReserva()
    {
        $path = public_path('ARCHIVO_BASE.xls');
        $data = file_get_contents(public_path('ARCHIVO_BASE.xls'));
        $pathToFile = public_path("ARCHIVO_BASE.xls");

        $name = 'ARCHIVO_BASE.xls';

        $headers = ['Content-Type: application/excel'];


        return response()->download($pathToFile, $name, $headers);

    }

    function listaActividades()
    {
        $datos = ordenes::listaActividades();
        return response()->json($datos);
    }
}
