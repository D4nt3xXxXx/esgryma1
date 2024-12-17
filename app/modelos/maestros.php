<?php
/**
 * Created by PhpStorm.
 * User: breynerperez
 * Date: 4/03/2020
 * Time: 10:18 PM
 */

namespace App\modelos;


use App\User;
use Illuminate\Http\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class maestros
{
    static function consultarAsistentes()
    {
        return DB::select("SELECT u.id, u.name AS text
FROM users u JOIN role_user ru ON u.id = ru.user_id
WHERE ru.role_id = 2;");
    }

    static function guardarCliente($datos, $idAsistente, $tipo = null)
    {
        DB::beginTransaction();
        try {
            if ($tipo == "nuevo") {
                $idCliente = DB::table("cliente")->insertGetId($datos);
                DB::table("asisadminxcliente")->insert(array('idCliente' => $idCliente, "idAsistenteAdmin" => $idAsistente));
            } elseif ($tipo == "actualizar") {
                $idCliente = $datos["idCliente"];
                $idAsistentexcliente = $datos["idAsistentexcliente"];
                unset($datos["idCliente"]);
                unset($datos["idAsistentexcliente"]);
                $actualizar = DB::table("cliente")->where("idCliente", $idCliente)->update($datos);
                DB::table("asisadminxcliente")->updateOrInsert(["id" => $idAsistentexcliente], array('idCliente' => $idCliente, "idAsistenteAdmin" => $idAsistente));
            }
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

    static function eliminarCliente($id)
    {
        $existe = DB::table("reserva")->where(["idCliente" => $id])->get();

        if (count($existe) == 0) {
            DB::table("cliente")->where("idCliente", $id)->delete();
            return 'ok';
        } else {
            return "El cliente no se puede eliminar porque esta asociado a la orden: " . $existe[0]->nroOrden;
        }
    }

    static function consultarActividades()
    {
        $datos = DB::select("SELECT a.idActividad,
       a.actividad,
       a.undMedida,
       a.codigoActividad,
       a.requiereInforme,
       a.tiempoinforme,
       a.idCategoria,
       a.idTema,
       a.idTipo,
       a.idGrupo,
       c.categoriaActividad AS categoria,
       t.temaActividad AS tema,
       ti.tipoActividad AS tipo,
       g.nomGrupo AS grupo
FROM actividad a
     JOIN categoria c ON c.idCategoria = a.idCategoria
     JOIN tema t ON t.idTema = a.idTema
     JOIN tipo ti ON ti.idTipo = a.idTipo
     JOIN grupo g ON g.idGrupo = a.idGrupo
     order by a.actividad asc");
        return $datos;
    }

    static function guardarActividad($datos)
    {
        $datos["undMedida"] = 'HORAS';
        $datos["tiempoinforme"] = !$datos["requiereInforme"] ? 0 : $datos["tiempoinforme"];
        DB::table("actividad")->updateOrInsert(array("idActividad" => $datos["idActividad"]), $datos);
        return 'ok';
    }

    static function consultarMatriz($idActividad)
    {
        $datos = DB::select("SELECT m.id,
       m.idGestor,
       m.idActividad,
       m.calificacion,
       u.name as gestor
FROM mtrcualificacion m
join users u on u.id=m.idGestor
where m.idActividad=$idActividad");
        return $datos;
    }

    static function eliminarGestorMatriz($id)
    {
        return DB::table("mtrcualificacion")->where("id", $id)->delete();
    }

    static function guardarGestorMatriz($datos)
    {
        return DB::table("mtrcualificacion")->insert($datos);
    }

    static function consultarUsuarios()
    {
        return DB::select("SELECT u.id,
       u.name as nombre,
       u.email as email,
       u.imagen as imagen,
       u.activo,
       u.tipo_usuario,
       GROUP_CONCAT(r.name ) as rol
FROM users u
left join role_user ru on ru.user_id=u.id
left join roles r on r.id=ru.role_id
group by u.id,u.name,u.email,u.imagen,u.activo,u.tipo_usuario");
    }

    static function eliminarUsuario($id)
    {
        DB::beginTransaction();
        try {
            User::where("id", $id)->delete();
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

    static function informacionUsuario($id)
    {
        $datosUsuario = DB::select("SELECT u.id,
       u.name as nombre,
       u.email as email,
       u.imagen as imagen,
       u.activo,
       u.tipo_usuario
FROM users u where u.id=$id");
        return [$datosUsuario];
    }

    static function guardarDatosUsuario($datos)
    {
        $id = $datos["datoUsuario"]["id"];
        $rolesAgregar = $datos["rolesAgregar"];
        $rolesEliminar = $datos["rolesEliminar"];

        if (count($rolesEliminar) > 0)
            DB::table("role_user")->where("user_id", $id)->whereIn("role_id", $rolesEliminar)->delete();
        if (count($rolesAgregar) > 0) {
            $tempRoles = [];
            foreach ($rolesAgregar as $item)
                array_push($tempRoles, array("role_id" => $item, 'user_id' => $id));
            DB::table("role_user")->insert($tempRoles);
        }

        return DB::table("users")->where("id", $id)->update($datos["datoUsuario"]);
    }

    static function actualizarImagen($imagen, $idUser)
    {
        $eliminar = "";
        $image_path = DB::table("users")->where("id", $idUser)->get()[0]->imagen;
        if (file_exists(public_path() . $image_path)) {
            @unlink(public_path() . $image_path);
        }
        DB::table("users")->where("id", $idUser)->update(array("imagen" => $imagen));
        return $eliminar;
    }

    static function eliminarFoto($idUser)
    {
        $image_path = DB::table("users")->where("id", $idUser)->get()[0]->imagen;
        if (file_exists(public_path() . $image_path)) {
            @unlink(public_path() . $image_path);
        }
        DB::table("users")->where("id", $idUser)->update(array("imagen" => null));
    }

    static function actualizarClave($id, $clave)
    {
        return DB::table("users")->where("id", $id)->update(array("password" => Hash::make($clave)));
    }

    static function listaPermisos()
    {
        return DB::select("SELECT p.id,
       p.name,
       p.slug,
       p.description,
       p.created_at,
       p.updated_at
FROM permissions p order by p.name asc;");
    }

    static function listaRoles()
    {
        return DB::select("SELECT r.id,
       r.name,
       r.slug,
       r.description,
       r.created_at,
       r.updated_at,
       r.special
FROM roles r order by r.name asc;");
    }

    static function permisosUsuario($id)
    {
        return DB::select("SELECT p.id,
       p.name,
       p.slug,
       p.description,
       p.created_at,
       p.updated_at
FROM permissions p
join permission_user pu on pu.permission_id=p.id and pu.user_id=$id;");
    }

    static function rolesUsuario($id)
    {
        return DB::select("SELECT r.id,
       r.name,
       r.slug,
       r.description,
       r.created_at,
       r.updated_at,
       r.special
FROM roles r
join role_user pu on pu.role_id =r.id and pu.user_id=$id;");
    }

    static function consultarTipoNovedad()
    {
        return DB::select("SELECT t.idTipoNovedad as id,
       t.tipoNovedad as nombre,
       t.colorNovedad as color,
       t.validacbox as rol
FROM tipo_novedad t order by t.tipoNovedad asc;");
    }

    static function guardarTipoNovedad($datos)
    {
        $id = $datos["idTipoNovedad"];
        return DB::table("tipo_novedad")->updateOrInsert(array("idTipoNovedad" => $id), $datos);
    }

    static function eliminarTipoNovedad($id)
    {
        try {
            DB::table("tipo_novedad")->where("idTipoNovedad", $id)->delete();
            return "Se eliminó el tipo de novedad con exito!";
        } catch (\Exception $es) {
            return $es->getMessage();
        }
    }

    static function consultarTipoActividad()
    {
        return DB::select("SELECT t.idTipo, t.tipoActividad, t.prefijo
FROM tipo t order by t.tipoActividad;");
    }

    static function guardarTipoActividad($datos)
    {
        $id = $datos["idTipo"];
        $datos["prefijo"] = strtoupper($datos["prefijo"]);
        return DB::table("tipo")->updateOrInsert(array("idTipo" => $id), $datos);
    }

    static function eliminarTipoActividad($id)
    {
        try {
            DB::table("tipo")->where("idTipo", $id)->delete();
            return "Se eliminó el tipo actividad con exito!";
        } catch (\Exception $es) {
            return $es->getMessage();
        }
    }

    static function consultarCategoria()
    {
        return DB::select("select c.idCategoria, c.categoriaActividad from categoria c order by c.categoriaActividad asc;");
    }

    static function guardarCategoria($datos)
    {
        $id = $datos["idCategoria"];
        return DB::table("categoria")->updateOrInsert(array("idCategoria" => $id), $datos);
    }

    static function eliminarCategoria($id)
    {
        try {
            DB::table("categoria")->where("idCategoria", $id)->delete();
            return "Se eliminó la categoria con exito!";
        } catch (\Exception $es) {
            return $es->getMessage();
        }
    }

    static function consultarGrupo()
    {
        return DB::select("select g.idGrupo, g.nomGrupo from grupo g order by g.nomGrupo asc;");
    }

    static function guardarGrupo($datos)
    {
        $id = $datos["idGrupo"];
        return DB::table("grupo")->updateOrInsert(array("idGrupo" => $id), $datos);
    }

    static function eliminarGrupo($id)
    {
        try {
            DB::table("grupo")->where("idGrupo", $id)->delete();
            return "Se eliminó el grupo con exito!";
        } catch (\Exception $es) {
            return $es->getMessage();
        }
    }

    static function consultarTema()
    {
        return DB::select("select t.idTema, t.temaActividad from tema t order by t.temaActividad asc;");
    }

    static function guardarTema($datos)
    {
        $id = $datos["idTema"];
        return DB::table("tema")->updateOrInsert(array("idTema" => $id), $datos);
    }

    static function eliminarTema($id)
    {
        try {
            DB::table("tema")->where("idTema", $id)->delete();
            return "Se eliminó el tema con exito!";
        } catch (\Exception $es) {
            return $es->getMessage();
        }
    }

    static function guardarUsuario($datos)
    {
        $email = $datos["email"];
        $existe = User::where("email", $email)->get();
        if (count($existe) > 0)
            return "El correo ingresado ya existe, intente con otro!";
        else {
            $datos["password"] = Hash::make($datos["password"]);
            $insert = User::create($datos);
        }

        return 'ok';
    }

    static function listaMetaMes()
    {
        return DB::select("select * from metames;");
    }

    static function guardarMetaMes($datos)
    {
        $anio = $datos["anio"];
        $mes = $datos["mes"];
        $existe = DB::table("metames")->where("anio", $anio)->where("mes", $mes)->get();
        if (count($existe) > 0) {
            return "Lo sentimos, ya hay una meta registrada para el mes seleccionado!";
        } else {
            DB::table("metames")->insert($datos);
            return 'ok';
        }

    }
}
