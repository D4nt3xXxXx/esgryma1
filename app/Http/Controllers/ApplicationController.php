<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function __invoke()
    {
        return view('application');
    }

    function autenticacion(Request $request)
    {
        $user = [];
        // SE VERIFICA SI EL USUARIO ESTA LOGUEADO
        if (Auth::check()) {
            // SE OBTIENEN LOS DATOS DEL USUARIO, ROLES Y PERMISOS
            $datosroles = Auth::user()->roles;
            $datospermisos = Auth::user()->permissions;
            $user = Auth::user();
            $user["roles"] = $datosroles;
            $user["permissions"] = $datospermisos;
            return response()->json(Auth::user());
        } else {
            return response()->json([]);
        }
    }
}
