<?php


namespace App\modelos;


use Illuminate\Support\Facades\DB;

class documentos
{
    static function guardarDocumento($datos)
    {
        $idReserva = $datos["idReserva"];
        $idOt = $datos["idOt"];
        $datos["created_at"] = date("Y-m-d H:i");
        $res = DB::table("doc_documentos")->insert($datos);
        //ACTUALIZAMOS EL ESTADO DEL INFORME
        gestor::actualizarInforme($idReserva, true);
        DB::table("ot")->where(['idOT' => $idOt])->update(["idEstadoOT" => 7, "estadoOT" => 23]);
        DB::table("reserva")->where(['idReserva' => $idReserva])->update(["idEstadoReserva" => 18]);
        return $res;
    }

    static function consultarDoc($idReserva, $idOt, $idGestor)
    {
        $datos = DB::table("doc_documentos")
            ->where("idReserva", $idReserva)
            ->where("idOt", $idOt)
            ->where("idGestor", $idGestor)
            ->get();
        return $datos;
    }

    static function eliminarDoc($id)
    {
        $datos = DB::table("doc_documentos")
            ->whereIn("id", $id)->delete();
    }
}
