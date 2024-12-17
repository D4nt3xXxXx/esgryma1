<?php

namespace App\Http\Controllers\salones;
use App\Http\Controllers\Controller;
use App\modelos\salones;
use Illuminate\Http\Request;

class salonesController extends Controller
{
    function listarEventosSalones()
    {
        $datos = salones::listarEventosSalones();
        $temp_datos = [];
        foreach ($datos as $item)
            array_push($temp_datos, array("title" => $item->actividad, "start" => $item->fechaActividad));
        return response()->json($temp_datos);
    }
}
