<?php

namespace App\Http\Controllers\gestor;

use App\Http\Controllers\Controller;
use App\modelos\gestor;
use App\Notifications\notificacion;
use App\Notifications\notificacionesLlegadaLlamada;
use App\User;
use Colors\RandomColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class gestorController extends Controller
{
    function listarOT(Request $request)
    {
        $id = Auth::user()->id;
        $idFiltro = $request->input("id");
        $opcion = $request->input("tipo");
        $datos = gestor::listarOT($id, $idFiltro, $opcion);
        return response()->json($datos);
    }

    function listarEventosAgenda(Request $request)
    {
        $idGestor = $request->input('idGestor');
        $datos = gestor::listarEventosAgenda($idGestor);

        $temp_datos = [];
        $agruparTipo = array_group_by($datos, "tipo");
        $colores = [];
        $gestoresLista = array_group_by($agruparTipo["OT"], 'Gestor');

        $gestoresTemp = [];
        $novedadesTemp = [];
        $colores = RandomColor::many(count($gestoresLista), array('format' => 'rgbCss', 'luminosity' => 'dark'));
        $cont = 0;
        foreach ($gestoresLista as $index => $item) {
            array_push($gestoresTemp, array("id" => strtoupper(trim(str_replace(' ', '', $index))), "text" => $index, "color" => $colores[$cont], "tipo" => "gestor"));
            /*foreach ($item as $item2) {
                array_push($temp_datos, array("tipo" => 'OT', "id" => $item2->idReserva, "title" => $item2->titulo, "start" => $item2->fechaInicio, "end" => $item2->fechaFinal, "color" => $colores[$cont], "classNames" => strtoupper(trim(str_replace(' ', '', $index)))));
            }*/
            $cont++;
        }

        /*if (isset($agruparTipo["NOVEDAD"])) {
            $cont = 0;
            $color_temp = array_group_by($agruparTipo["NOVEDAD"], 'novedad');
            $colores = RandomColor::many(count($color_temp), array('format' => 'rgbCss', 'luminosity' => 'light'));
            $id = "";
            foreach ($color_temp as $index => $item) {
                $id = $item[0]->id . trim(str_replace(' ', '', $index));
                array_push($novedadesTemp, array("id" => $id, "text" => $item[0]->novedad, "color" => $colores[$cont], "tipo" => 'novedad'));
                foreach ($item as $item2) {
                    array_push($temp_datos, array("tipo" => 'NOVEDAD', "id" => $item2->id, "title" => $item2->titulo, "start" => $item2->fechaInicio, "end" => $item2->fechaFinal, "color" => $colores[$cont], "classNames" => $id, "gestor" => strtoupper(trim(str_replace(' ', '', $item2->Gestor))), "idGestor" => $item2->idGestor));
                }
                $cont++;
            }
        }*/
        return response()->json([$temp_datos, $gestoresTemp, $novedadesTemp]);
    }

    function listarEventosAgendaGestor(Request $request)
    {
        $idGestor = null;
        if (!empty($request->input('idGestor')))
            $idGestor = $request->input('idGestor');
        else
            $idGestor = Auth::user()->id;

        $datos = gestor::listarEventosAgendaGestor($idGestor);

        $temp_datos = [];
        $agruparTipo = array_group_by($datos, "tipo");
        $colores = [];
        if (count($datos) > 0) {
            if (isset($agruparTipo["OT"])) {
                array_push($colores, array("titulo" => 'OT', "color" => $agruparTipo["OT"][0]->color));
                foreach ($agruparTipo["OT"] as $item) {
                    array_push($temp_datos, array("tipo" => 'OT', "id" => $item->idReserva, "title" => $item->titulo, "start" => $item->fechaInicio, "end" => $item->fechaFinal, "color" => $item->color));
                }
                if (isset($agruparTipo["NOVEDAD"])) {
                    $color_temp = array_group_by($agruparTipo["NOVEDAD"], 'color');
                    foreach ($color_temp as $index => $item) {
                        array_push($colores, array("titulo" => $item[0]->novedad, "color" => $index));
                    }
                    foreach ($agruparTipo["NOVEDAD"] as $item) {
                        array_push($temp_datos, array("tipo" => 'NOVEDAD', "id" => $item->id, "title" => $item->titulo, "start" => $item->fechaInicio, "end" => $item->fechaFinal, "color" => $item->color));
                    }
                }
            }
        }
        return response()->json([$temp_datos, $colores]);
    }

    function filtroEventosAgenda(Request $request)
    {
        $idGestor = $request->input('gestores');
        $listaGestores = $request->input("listaGestores");
        $datos = gestor::listarEventosAgenda($idGestor);
        $coloresGestores = collect($listaGestores)->whereIn("id", $idGestor)->pluck("color");
        $gestoresTemp = [];
        $novedadesTemp = [];
        $tempLeyendas = [];
        $temp_datos = [];
        if (!empty($datos)) {
            $agruparTipo = array_group_by($datos, "tipo");
            $colores = [];
            $gestoresLista = array_group_by($agruparTipo["OT"], 'Gestor');

            $colores = RandomColor::many(count($gestoresLista), array('format' => 'rgbCss', 'luminosity' => 'dark'));
            $cont = 0;
            foreach ($gestoresLista as $index => $item) {
                array_push($gestoresTemp, array("id" => strtoupper(trim(str_replace(' ', '', $index))), "text" => $index, "color" => $colores[$cont], "tipo" => "gestor"));
                foreach ($item as $item2) {
                    array_push($temp_datos, array("tipo" => 'OT', "id" => $item2->idReserva, "title" => $item2->titulo, "start" => $item2->fechaInicio, "end" => $item2->fechaFinal, "color" => $coloresGestores[$cont], "classNames" => strtoupper(trim(str_replace(' ', '', $index)))));
                }
                $cont++;
            }

            if (isset($agruparTipo["NOVEDAD"])) {
                $cont = 0;
                $color_temp = array_group_by($agruparTipo["NOVEDAD"], 'novedad');
                $colores = RandomColor::many(count($color_temp), array('format' => 'rgbCss', 'luminosity' => 'light'));
                $id = "";
                foreach ($color_temp as $index => $item) {
                    $id = $item[0]->id . trim(str_replace(' ', '', $index));
                    array_push($novedadesTemp, array("id" => $id, "text" => $item[0]->novedad, "color" => $colores[$cont], "tipo" => 'novedad'));
                    foreach ($item as $item2) {
                        array_push($temp_datos, array("tipo" => 'NOVEDAD', "id" => $item2->id, "title" => $item2->titulo, "start" => $item2->fechaInicio, "end" => $item2->fechaFinal, "color" => $colores[$cont], "classNames" => $id, "gestor" => strtoupper(trim(str_replace(' ', '', $item2->Gestor))), "idGestor" => $item2->idGestor));
                    }
                    $cont++;
                }
            }
            if(!empty($novedadesTemp))
                $tempLeyendas=collect($novedadesTemp)->pluck("id");
        }
        return response()->json(["datos" => $temp_datos, "leyendas" => $novedadesTemp, "gestores" => $idGestor,"selectLeyendas"=>$tempLeyendas]);
    }

    function actualizarEncuesta(Request $request)
    {
        $idReserva = $request->input("idReserva");
        $encuestaOK = $request->input("encuesta");
        $datos = gestor::actualizarEncuesta($idReserva, $encuestaOK);
        return response()->json($datos);
    }

    function actualizarInforme(Request $request)
    {
        $idReserva = $request->input("idReserva");
        $informeOK = $request->input("informe");
        $datos = gestor::actualizarInforme($idReserva, $informeOK);
        return response()->json($datos);
    }

    function guardarOrdenRealizada(Request $request)
    {
        $estadoOT = $request->input('estadoOT');
        $estadoReserva = $request->input("estadoReserva");
        $idReserva = $request->input("idReserva");
        $idOT = $request->input('idOT');
        $res = gestor::guardarOrdenRealizada($estadoReserva, $estadoOT, $idReserva, $idOT);
        return response()->json($res);
    }

    function actualizarEstados(Request $request)
    {
        $idOT = $request->input("idOT");
        $valor = $request->input("valor");
        $opcion = $request->input("opcion");
        $datosOT = $request->input("datosOT");

        $res = gestor::actualizarEstados($idOT, $valor, $opcion);
        if ($valor == true) {
            $user = User::find($datosOT["idAsistente"]);
            $titulo = ($opcion == 'llamada' ? 'Confirmación llegada' : 'Confirmación llamada');
            $mensaje = ($opcion == 'llamada' ? "Se ha confirmado la llegada del gestor " . $datosOT["Gestor"] . ', con referencia de la OT: ' . $datosOT["nroorden"] : "Se ha confirmado la llamada del gestor " . $datosOT["Gestor"] . ' al cliente solicitado, con referencia de la OT: ' . $datosOT["nroorden"]);
            $user->notify(new notificacionesLlegadaLlamada(array("titulo" => $titulo, "mensaje" => $mensaje)));
        }
        return response()->json($res);
    }
}
