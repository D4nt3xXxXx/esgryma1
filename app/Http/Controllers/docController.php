<?php

namespace App\Http\Controllers;

use App\Http\Controllers\notificaciones\notificacionesController;
use App\modelos\compras;
use App\modelos\consultas;
use App\modelos\documentos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class docController extends Controller
{
    function guardarDocumento(Request $request)
    {
        $datosInfo = $request->all();
        $directorio = env('GOOGLE_DRIVE_FOLDER_ID');
        $usuario = $datosInfo["idGestor"];
        $idReserva = $datosInfo["idReserva"];
        $idOt = $datosInfo["idOT"];
        $path = [];
        $this->eliminarArchivo($idReserva, $idOt, $usuario);
        foreach ($request->file('archivo') as $index => $item) {
            $extension = $item->extension();
            $nombreArchivo = $this->nombreArchivo($datosInfo["idReserva"], $datosInfo["idOT"], $extension);
            $tempPath = $item->storeAs(
                '/' . $directorio, $nombreArchivo, array("disk"=>'google')
            );
            $datosInserDctoDetalle = array("nombre" => $nombreArchivo, "path" => $tempPath, "ext" => $extension, "idGestor" => $usuario, "idReserva" => $idReserva, "idOt" => $idOt);
            documentos::guardarDocumento($datosInserDctoDetalle);
            array_push($path, $tempPath);
        }
        return response()->json($path);
    }

    function crearDirectorio($directorio)
    {
        Storage::cloud()->makeDirectory('/' . $directorio);
        $contenido = collect(Storage::cloud()->listContents('/', false));
        $res = $this->validarExisteDir($contenido, $directorio);
        return $res;
    }

    function nombreArchivo($idReserva, $idOt, $ext)
    {
        $tempTipoDcto = $idReserva . "_" . $idOt . "_";
        return $tempTipoDcto . date('Y') . date('m') . date('d') . date('H') . date('i') . date('s') . '_' . rand(0, 10000) . ".$ext";
    }

    function rutaDrive($path, $nombre)
    {
        $dir = '/' . env('GOOGLE_DRIVE_FOLDER_ID') . '/' . explode('/', $path)[0];
        $recursive = false; // Get subdirectories also?
        $contents = collect(Storage::cloud()->listContents($dir, $recursive));
//        return response()->json($contents);
        $file = $contents
            ->where('type', '=', 'file')
            ->where('filename', '=', pathinfo($nombre, PATHINFO_FILENAME))
            ->where('extension', '=', pathinfo($nombre, PATHINFO_EXTENSION))
            ->first(); // there can be duplicate file names!
        //return $file; // array with file info
        $url = Storage::cloud()->url($file['path']);
        return array("url" => $url, "path" => $file['path']);
    }

    function consultarDocumentos(Request $request)
    {
        $idReserva = $request->input("idReserva");
        $idOt = $request->input("idOt");
        $idGestor = $request->input("idGestor");
        $datos = documentos::consultarDoc($idReserva, $idOt, $idGestor);
        return response()->json($datos);
    }

    function verDcto(Request $request)
    {
        $path = $request->input("path");
        $filename = $request->input("nombre");

        $dir = '/' . env('GOOGLE_DRIVE_FOLDER_ID');
        $recursive = false; // Get subdirectories also?
        $contents = collect(Storage::cloud()->listContents($dir, $recursive));
//        return response()->json($contents);
        $file = $contents
            ->where('type', '=', 'file')
            ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
            ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
            ->first(); // there can be duplicate file names!

        //return $file; // array with file info
        $url = Storage::cloud()->url($file['path']);
        return response()->json($url);
        $rawData = Storage::cloud()->get($file['path']);

        return response($rawData, 200)
            ->header('ContentType', $file['mimetype'])
            ->header('Content-Disposition', "attachment; filename=$filename");
    }

    function eliminarArchivo($idReserva, $idOt, $idGestor)
    {
        $archivos = documentos::consultarDoc($idReserva, $idOt, $idGestor);
        if (!empty($archivos)) {
            $dir = '/' . env('GOOGLE_DRIVE_FOLDER_ID');
            $recursive = false; // Get subdirectories also?
            $contents = collect(Storage::cloud()->listContents($dir, $recursive));
            $ids = [];
            foreach ($archivos as $item) {
                array_push($ids, $item->id);
                $filename = $item->nombre;
                $file = $contents
                    ->where('type', '=', 'file')
                    ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
                    ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
                    ->first(); // there can be duplicate file names!

                Storage::cloud()->delete($file['path']);
            }
            documentos::eliminarDoc($ids);
        }
    }

    function descargarDcto(Request $request)
    {
        $path = $request->input("path");
        $filename = $request->input("nombre");
        $ext = $request->input("ext");

        $dir = '/' . env('GOOGLE_DRIVE_FOLDER_ID');
        $recursive = false; // Get subdirectories also?
        $contents = collect(Storage::cloud()->listContents($dir, $recursive));

        $file = $contents
            ->where('type', '=', 'file')
            ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
            ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
            ->first(); // there can be duplicate file names!

        $readStream = Storage::cloud()->getDriver()->readStream($file['path']);

        return response()->stream(function () use ($readStream) {
            fpassthru($readStream);
        }, 200, [
            'Content-Type' => $file['mimetype'],
            'Content-disposition' => 'attachment; filename="' . $filename . '.' . $ext . '"', // force download?
        ]);
    }
}
