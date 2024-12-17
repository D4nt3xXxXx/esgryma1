<?php

namespace App\Http\Controllers\maestros;

use App\Http\Controllers\Controller;
use App\modelos\maestros;
use Colors\RandomColor;
use Illuminate\Http\Request;

class maestrosController extends Controller
{
    //
    function consultarAsistentes()
    {
        $datos = maestros::consultarAsistentes();
        return response()->json($datos);
    }

    function guardarCliente(Request $request)
    {
        $datos = $request->input("datos");
        $idAsistente = $request->input("idAsistente");
        $tipo = $request->input("tipo");
        $res = maestros::guardarCliente($datos, $idAsistente, $tipo);
        return response()->json($res);
    }

    function eliminarCliente(Request $request)
    {
        $idCliente = $request->input("idCliente");
        $res = maestros::eliminarCliente($idCliente);

        return response()->json($res);
    }

    function consultarActividades()
    {
        $datos = maestros::consultarActividades();
        return response()->json($datos);
    }

    function guardarActividad(Request $request)
    {
        $res = maestros::guardarActividad($request->all());
        return response()->json($res);
    }

    function consultarMatriz(Request $request)
    {
        $idActividad = $request->input("idActividad");
        $datos = maestros::consultarMatriz($idActividad);
        return response()->json($datos);
    }

    function eliminarGestorMatriz(Request $request)
    {
        $id = $request->input("id");
        return response()->json(maestros::eliminarGestorMatriz($id));
    }

    function guardarGestorMatriz(Request $request)
    {
        $datos = $request->all();
        return response()->json(maestros::guardarGestorMatriz($datos));
    }

    function consultarUsuarios()
    {
        $datos = maestros::consultarUsuarios();
        return response()->json($datos);
    }

    function eliminarUsuario(Request $request)
    {
        $idUser = $request->input("id");
        $res = maestros::eliminarUsuario($idUser);
        return response()->json($res);
    }

    function informacionusuario(Request $request)
    {
        $idUser = $request->input("id");
        $datos = maestros::informacionUsuario($idUser);
        return response()->json($datos);
    }

    function actualizarUsuario()
    {

    }

    function guardarDatosUsuario(Request $request)
    {
        $datos = $request->all();
        return response()->json(maestros::guardarDatosUsuario($datos));
    }

    function actualizarImagen(Request $request)
    {
        $idUser = $request->input('id');
        $imageName = time() . '.' . $request->imagen->getClientOriginalExtension();
        $request->imagen->move(public_path('images/profile'), $imageName);
        $res = maestros::actualizarImagen('/images/profile/' . $imageName, $idUser);
        return response()->json($res);
    }

    function eliminarFoto(Request $request)
    {
        $idUser = $request->input("id");
        maestros::eliminarFoto($idUser);
        return response()->json("ok");
    }

    function actualizarClave(Request $request)
    {
        $idUser = $request->input("id");
        $clave = $request->input("clave");
        maestros::actualizarClave($idUser, $clave);
    }

    function listaPermisos()
    {
        return response()->json(maestros::listaPermisos());
    }

    function listaRoles()
    {
        return response()->json(maestros::listaRoles());
    }

    function permisosUsuario(Request $request)
    {
        $idUser = $request->input("id");
        return maestros::permisosUsuario($idUser);
    }

    function rolesUsuario(Request $request)
    {
        $idUser = $request->input("id");
        return maestros::rolesUsuario($idUser);
    }

    function consultarTipoNovedad()
    {
        $datos = maestros::consultarTipoNovedad();
        return response()->json($datos);
    }

    function consultarColores()
    {
        $colores = RandomColor::many(36, array('format' => 'hex'));
        $temp_colores = [];
        foreach ($colores as $item) {
            array_push($temp_colores, array("id" => $item, "text" => $item));
        }
        return response()->json($temp_colores);
    }

    function guardarTipoNovedad(Request $request)
    {
        $datos = $request->all();
        $res = maestros::guardarTipoNovedad($datos);
        return response()->json($res);
    }

    function eliminarTipoNovedad(Request $request)
    {
        $id = $request->input("id");
        return response()->json(maestros::eliminarTipoNovedad($id));
    }

    function consultarTipoActividad()
    {
        $datos = maestros::consultarTipoActividad();
        return response()->json($datos);
    }

    function guardarTipoActividad(Request $request)
    {
        $datos = $request->all();
        $res = maestros::guardarTipoActividad($datos);
        return response()->json($res);
    }

    function eliminarTipoActividad(Request $request)
    {
        $id = $request->input("id");
        return response()->json(maestros::eliminarTipoActividad($id));
    }

    function consultarCategoria()
    {
        $datos = maestros::consultarCategoria();
        return response()->json($datos);
    }

    function guardarCategoria(Request $request)
    {
        $datos = $request->all();
        $res = maestros::guardarCategoria($datos);
        return response()->json($res);
    }

    function eliminarCategoria(Request $request)
    {
        $id = $request->input("id");
        return response()->json(maestros::eliminarCategoria($id));
    }

    function consultarGrupo()
    {
        $datos = maestros::consultarGrupo();
        return response()->json($datos);
    }

    function guardarGrupo(Request $request)
    {
        $datos = $request->all();
        $res = maestros::guardarGrupo($datos);
        return response()->json($res);
    }

    function eliminarGrupo(Request $request)
    {
        $id = $request->input("id");
        return response()->json(maestros::eliminarGrupo($id));
    }

    function consultarTema()
    {
        $datos = maestros::consultarTema();
        return response()->json($datos);
    }

    function guardarTema(Request $request)
    {
        $datos = $request->all();
        $res = maestros::guardarTema($datos);
        return response()->json($res);
    }

    function eliminarTema(Request $request)
    {
        $id = $request->input("id");
        return response()->json(maestros::eliminarTema($id));
    }

    function guardarUsuario(Request $request)
    {
        $datos = $request->all();
        return response()->json(maestros::guardarUsuario($datos));
    }

    function consultarListaMetaMes()
    {
        $datos = maestros::listaMetaMes();
        return response()->json($datos);
    }

    function guardarMetaMes(Request $request)
    {
        return response()->json(maestros::guardarMetaMes($request->all()));
    }
}
