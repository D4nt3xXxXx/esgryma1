<?php

namespace App\modelos;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;

class ordenes extends Model
{
    static function ordenesAsistente($asistente)
    {
        return DB::select("select * from view_reservas where IdAsistenteAdmin=$asistente");
    }

    static function ListaCategoria($idCategoria)
    {
        return DB::select("select idCategoria as id,categoriaActividad as text from categoria where idCategoria=$idCategoria");
    }

    static function ListaSalon()
    {
        return DB::select("select idSalon as id,nombreSalon as text from salon");
    }

    static function listaActividades($idTipoActividad = null)
    {
        if ($idTipoActividad != null)
            return DB::select("select idActividad as id,actividad as text,idTema,idCategoria from actividad where idTipo='$idTipoActividad'");
        else
            return DB::select("select idActividad as id,actividad as text,idTema,idCategoria from actividad");
    }

    static function listaGestores($actividad)
    {
        return DB::select("select mtrcualificacion.idGestor as id,users.name as text
from mtrcualificacion
join users on mtrcualificacion.idGestor=users.id
where mtrcualificacion.idActividad='$actividad' and users.activo=1");
    }

    static function existeOrden($numOrden)
    {
        return DB::select("select idReserva from ot where idReserva='$numOrden'");
    }

    static function guardarOrden($datos, $participantes)
    {
        $idOt = 0;
        $estado = $datos["idEstadoReserva"];
        $idReserva = $datos["idReserva"];
        $fechaActividad = $datos["fechaActividad"];
        DB::beginTransaction();
        try {
            if ($datos["idEstadoReserva"] == 2)
                $estado = 3;
            elseif ($datos["idEstadoOT"] == 5)
                $estado = 14;

            DB::table("reserva")->where(['idReserva' => $idReserva])->update(["idEstadoReserva" => $estado]);
            unset($datos["idEstadoReserva"]);
            DB::table("ot")->updateOrInsert(['idOT' => $datos['idOT']], $datos);
            DB::commit();
            //OBTENEMOS EL ID DE OT PARA CUANDO SEA POR PRIMERA VEZ
            if (empty($datos["idOT"])) {
                $idOt = DB::table("ot")->where(["idReserva" => $idReserva])->get()[0]->idOT;
            } else {
                $idOt = $datos['idOT'];
            }
            $res = self::guardarParticipantes($idOt, $participantes);
            if (!empty($fechaActividad)) {
                //ACTUALIZAMOS LA RESERVA EN EL PLAN CUANDO SEA UN MES DIFERENTE AL PLANEADO
                self::actualizarFechaPlan($idReserva, $fechaActividad);
            }
            return 'ok';
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        } catch (\Throwable $e) {
            DB::rollback();
            return $e->getMessage();
        }

    }

    static function actualizarFechaPlan($idReserva, $fechaActividad)
    {
        $mes = date("m", strtotime($fechaActividad));
        $anio = date("Y", strtotime($fechaActividad));
        return DB::select("call actualizarFechaPlan($idReserva,$mes,$anio);");
    }

    static function guardarParticipantes($idOt, $datos)
    {
        try {
            if (count($datos)) {
                $temp_datos = [];
                foreach ($datos as $index => $item) {
                    if ($item["estado"] == "Nuevo") {
                        $item["idOT"] = $idOt;
                        $item["idParticipante"] = 0;
                        unset($item["estado"]);
                        array_push($temp_datos, $item);
                    }
                }
                DB::table('participante')->insert($temp_datos);
                return $temp_datos;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    static function listarTipoActividad()
    {
        return DB::select("select idTipo as id,tipoActividad as text from tipo order by tipoActividad asc");
    }

    static function listarTema($idTema)
    {
        return DB::select("select idTema as id, temaActividad as text from tema where idTema=$idTema");
    }

    static function actualizarEstadoReservaOt($idReserva, $idOt)
    {
        DB::table("reserva")->where(['idReserva' => $idReserva])->update(["idEstadoReserva" => 14]);
        DB::table("ot")->where(['idOT' => $idOt])->update(["idEstadoOT" => 5]);
        return 'ok';
    }

    static function listarParticiapantes($idOt)
    {
        return DB::select("SELECT p.idParticipante,
       p.cedula,
       p.nombre,
       p.empresa,
       p.tipo,
       p.facturado,
       p.idOT,
       'Registrado' as estado
FROM participante p where p.idOT=$idOt");
    }

    static function guardarParticipante($datos)
    {
        $id = DB::table('participante')->insertGetId($datos);
        return DB::table("particapante")->find($id);
    }

    static function eliminarParticipante($id)
    {
        return DB::delete("delete from participante where idParticipante=$id");
    }

    static function actualizarFacturado($id, $facturado)
    {
        return DB::table("participante")->where('idParticipante', $id)->update(["facturado" => $facturado]);
    }

    static function listarBitacoras($idOT)
    {
        return DB::select("SELECT b.idBitacora,
       b.nroOrden,
       b.idOT,
       b.idReserva,
       b.fechaObs,
       b.Observacion,
       b.idUser,
       u.name as nombre
FROM bitacora b
join users u on u.id=b.idUser
where b.idOT=$idOT");
    }

    static function guardarBitacora($datos)
    {
        $datos["fechaObs"] = date("Y-m-d H:i");
        $datos["idUser"] = Auth::user()->id;
        return DB::table("bitacora")->insert($datos);
    }

    static function cancelarOT($idOt, $idReserva)
    {
        DB::beginTransaction();
        try {
            DB::table("ot")->where("idOT", $idOt)->update(array("idEstadoOT" => 12));
            DB::table("reserva")->where("idReserva", $idReserva)->update(array("idEstadoReserva" => 10));
            DB::select("call cancelarOT($idReserva,@resu);");
            DB::commit();
            return 'ok';
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    static function actualizarOtCancelada($datos)
    {
        $idReserva = $datos["idReserva"];
        $idOT = $datos["idOT"];
        DB::beginTransaction();
        try {
            DB::table("ot")->where("idOT", $idOT)->update(array("idEstadoOT" => 4));
            DB::table("reserva")->where("idReserva", $idReserva)->update(array("idEstadoReserva" => 3));
            DB::commit();
            return 'ok';
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    static function eliminarReserva($idReserva)
    {
        DB::beginTransaction();
        try {
            DB::table("detalleplan")->where("idReserva", $idReserva)->delete();
            DB::table("ot")->where("idReserva", $idReserva)->delete();
            DB::table("reserva")->where("idReserva", $idReserva)->delete();
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

    static function descargarDatos()
    {
        return DB::select("select * from view_info_reservas;");
    }
}
