<?php

namespace App\Http\Controllers\ordenes;

use App\Http\Controllers\Controller;
use App\modelos\ordenes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ordenesController extends Controller
{
    //
    function listaCategoria(Request $request)
    {
        $idCategoria = $request->input("idCategoria");
        $datos = ordenes::listaCategoria($idCategoria);
        return response()->json($datos);
    }

    function listaSalon()
    {
        $datos = ordenes::listaSalon();
        return response()->json($datos);
    }

    function listaActividades(Request $request)
    {
        $idTipoActividad = $request->input("idTipoActividad");
        $datos = ordenes::listaActividades($idTipoActividad);
        return response()->json($datos);
    }

    function listaGestores(Request $request)
    {
        $idActividad = $request->input("idActividad");
        $datos = ordenes::listaGestores($idActividad);
        return response()->json($datos);
    }


    function listarOrdenes(Request $request)
    {
        // return 0;
        $idAsistente = Auth::user()->id;
        $datos = ordenes::ordenesAsistente($idAsistente);
        // return $datos;
        $tipoReserva = array_group_by($datos, 'tipo_Reserva');
        $clientes = array_group_by($datos, 'nombreCliente');
        $estados = array_group_by($datos, 'nomestadoot');
        $asistentes = array_group_by($datos, 'AsistenteAdmin');
        $empresas = array_group_by($datos, 'empresaOrden');
        $filtroTipoReserva = [];
        $filtroClientes = [];
        $filtroEstados = [];
        $filtroAsistentes = [];
        $filtroEmpresa = [];
        $todos = array("label" => "Todos", "value" => "all");
        array_push($filtroTipoReserva, $todos);
        array_push($filtroClientes, $todos);
        array_push($filtroEstados, $todos);
        array_push($filtroAsistentes, $todos);
        array_push($filtroEmpresa, $todos);

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
        foreach ($empresas as $index => $item) {
            array_push($filtroEmpresa, array("label" => $index, "value" => $index));
        }
        return response()->json([
            "datos" => $datos,
            "filtroTipoReserva" => $filtroTipoReserva,
            "filtroClientes" => $filtroClientes,
            "filtroEstados" => $filtroEstados,
            "filtroAsistente" => $filtroAsistentes,
            "filtroEmpresas" => $filtroEmpresa
        ]);
    }

    function guardarOrden(Request $request)
    {
        $datos = $request->input("orden");
        $participantes = $request->input("participantes");
        $nroOrden = $datos["idReserva"];
        $fecIniOrden = date("Y-m-d H:i");//date("Y-m-d", strtotime($datos["fecIniOrden"]));
        $fecFinOrden = date("Y-m-d H:i");//date("Y-m-d", strtotime($datos["fecFinOrden"]));
        $fecha = date("Y-m-d H:i");

        $fa = empty($datos["fechaActividad"]) ? date('Y-m-d H:i') : $datos["fechaActividad"];
        $fa = date("Y-m-d H:i", strtotime($fa));
        $fechaInforme = empty($datos["fechaInforme"]) ? null : date("Y-m-d H:i", strtotime($datos["fechaInforme"]));
        $fechaFinActividad = empty($datos["fechaFinActividad"]) ? null : date("Y-m-d H:i", strtotime($datos["fechaFinActividad"]));
        // return $fa;

        $temp_datos = array(
            'idGestor' => $datos["idGestor"],
            'idReserva' => $nroOrden,
            'idActividad' => $datos["idActividad"],
            'fechaActividad' => $fa,
            'asesorARL' => $datos["asesorARL"],
            'tipoActividad' => $datos["tipoActividad"],
            'tiempoEjecutar' => $datos["tiempoEjecutar"],
            'fechaInforme' => $fechaInforme,
            'direccion' => $datos["direccion"],
            'coordDireccion' => $datos["coordDireccion"],
            'nombreContacto' => $datos["nombreContacto"],
            "estadoOT" => $datos["estadoOT"],
            'observaciones' => $datos["observaciones"],
            'facturada' => !$datos["facturada"] ? 0 : 1,
            'urlInforme' => $datos["urlInforme"],
            'idEstadoOT' => $datos["idEstadoOT"],
            "idOT" => empty($datos["idOT"]) ? null : $datos["idOT"],
            'idSalon' => $datos["idSalon"],
            'idEstadoReserva' => $datos["idEstadoReserva"],
            "telContacto" => $datos["telefonocontacto"],
            "ciudad" => $datos["ciudad"],
            "idcurso" => $datos["idcurso"],
            "fechaFinActividad" => $fechaFinActividad,
            'created_at' => $fecha,
            'updated_at' => $fecha,
        );

        /*$existenroOrden = ordenes::existeOrden($nroOrden);
        if (empty($existenroOrden)) {*/
        // return $temp_datos;
        $res = ordenes::guardarOrden($temp_datos, $participantes);
        return response()->json($res);
        /*} else {
            return response()->json("Lo sentimos la orden gestionada ya existe.");
        }*/
    }

    function listarTipoActividad()
    {
        $datos = ordenes::listarTipoActividad();
        return response()->json($datos);
    }

    function listarTema(Request $request)
    {
        $idTema = $request->input("idTema");
        $datos = ordenes::listarTema($idTema);
        return response()->json($datos);
    }

    function programarOt(Request $request)
    {
        $idReserva = $request->input("idReserva");
        $idOt = $request->input("idOt");
        ordenes::actualizarEstadoReservaOt($idReserva, $idOt);
        return response()->json(true);
    }

    function listarParticiapantes(Request $request)
    {
        $idOt = $request->input('idOt');
        $datos = ordenes::listarParticiapantes($idOt);
        return response()->json($datos);
    }

    function guardarParticiapantes(Request $request)
    {
        $datos = $request->all();
        $resDatos = ordenes::guardarParticipante($datos);
        return response()->json($resDatos);
    }

    function eliminarParticipante(Request $request)
    {
        $idParticipante = $request->input('idParticipante');
        ordenes::eliminarParticipante($idParticipante);
        return response()->json(true);
    }

    function actualizarFacturado(Request $request)
    {
        $datos = $request->all();
        $idParticipante = $datos["idParticipante"];
        $facturado = $datos["facturado"];

        return response()->json(ordenes::actualizarFacturado($idParticipante, $facturado));
    }

    function listarBitacoras(Request $request)
    {
        $idOT = $request->input("idOT");
        $datos = ordenes::listarBitacoras($idOT);
        return response()->json($datos);
    }

    function guardarBitacora(Request $request)
    {
        $datos = $request->all();
        $res = ordenes::guardarBitacora($datos);
        return response()->json($res);
    }

    function cancelarOT(Request $request)
    {
        $idOT = $request->input("idOT");
        $idReserva = $request->input("idReserva");
        $res = ordenes::cancelarOT($idOT, $idReserva);
        return response()->json($res);
    }

    function actualizarOtCancelada(Request $request)
    {
        $datos = $request->all();
        $res = ordenes::actualizarOtCancelada($datos);
        return response()->json($res);
    }

    function eliminarReserva(Request $request)
    {
        $idReserva = $request->input("idReserva");
        $res = ordenes::eliminarReserva($idReserva);
        return response()->json($res);
    }

    function descargarDatos()
    {
        $datos = ordenes::descargarDatos();
        return response()->json($datos);
    }
}
