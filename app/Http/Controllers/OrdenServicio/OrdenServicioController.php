<?php

namespace App\Http\Controllers\OrdenServicio;

use App\OrdenServicio\OrdenServicio;
use App\Imports\OsImport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrdenServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    function import()
    {
        //Excel::import(new UsersImport, request()->file('file'));
        $array = Excel::toCollection(new UsersImport, request()->file('file'));
        return response()->json($array);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $osArray = Excel::toCollection(new OsImport, request()->file('archivo_excel'));
        $idCliente = $request->input("cliente");
        $fechaInicio = $request->input("fechaInicio");
        $fechaFinal = $request->input("fechaFinal");
        $fechaManual = $request->input("fechaManual");
        // validar año que suben en las fechas.
        // titulos a columnas.
        $osArrayNroOrden = [];
        $osArrayOrdenConCampoVacio = [];
        $osArrayOrdenFechaErronea = [];
        $fechaActual = new \DateTime();
        $fechaRegistro = date('Y-m-d H:i');
        foreach ($osArray as $index => $osA) {
            foreach ($osA as $index2 => $os) {
                if ($index2 > 0)
                    $osArrayNroOrden[] = $os[0];
            }
        }

        if (count($osArrayNroOrden) > count(array_unique($osArrayNroOrden))) {
            $repetidos = array_values(array_diff_assoc($osArrayNroOrden, array_unique($osArrayNroOrden)));
            $data = array('mensaje' => 'Se encontraron las siguientes ordenes repetidas en el archivo excel', 'data' => $repetidos);
            return response()->json($data, 200);
        }

        // Aquí mismo agregar la validación de las fechas.
        foreach ($osArray as $index => $osA) {
            foreach ($osA as $index2 => $os) {
                if ($index2 > 0) {
                    if (empty($os[0]) or empty($os[1]) or empty($os[2]) or empty($os[3]) or empty($os[4]) or trim($os[5]) == '' or trim($os[6]) == '' or empty($os[7]) or empty($os[8])) {
                        if (empty($os[0])) {
                            $data = array('mensaje' => 'Existen ordenes sin numero, corregir y subir de nuevo');
                            return response()->json($data, 200);
                        } else {
                            $osArrayOrdenConCampoVacio[] = $os[0];
                        }
                    }
                    if (date("Y", strtotime($os[3])) <> $fechaActual->format('Y') /*or date("m", strtotime($os[3])) < $fechaActual->format('m')*/) {
                        $osArrayOrdenFechaErronea[] = $os[0];
                    }
                }
            }
        }

        if (count($osArrayOrdenConCampoVacio) > 0) {
            $data = array('mensaje' => 'Las siguientes ordenes tienen campos importantes vacios', 'data' => $osArrayOrdenConCampoVacio);
            return response()->json($data, 200);
        }
        if (count($osArrayOrdenFechaErronea) > 0) {
            $data = array('mensaje' => 'Las siguientes ordenes tienen fechas erroneas', 'data' => $osArrayOrdenFechaErronea);
            return response()->json($data, 200);
        }

        $osExisten = DB::TABLE('reserva')
            ->SELECT('nroOrdenPadre as nroOrden')
            ->whereIn('nroOrdenPadre', $osArrayNroOrden)
            ->distinct()
            ->get();
        // $osArrayNroOrden = join(",",$osArrayNroOrden);
        // $osExisten =  DB::SELECT(DB::raw("SELECT * FROM RESERVA WHERE nroOrden IN (?)",$osArrayNroOrden));
        // RETURN $osExisten;
        if (count($osExisten) > 0) {
            $data = array(
                'mensaje' => 'Las siguientes ordenes ya existen con anterioridad, corregir y subir de nuevo',
                'data' => $osExisten
            );
        } else {
            DB::beginTransaction();
            try {
                foreach ($osArray as $index => $osA) {
                    foreach ($osA as $index2 => $os) {
                        if ($index2 > 0) {
                            if (empty($os[9])) {
                                $gestor = '';
                            } else {
                                $gestor = $os[9];
                            }
                            if (empty($os[10])) {
                                $observacion = '';
                            } else {
                                $observacion = $os[10];
                            }

                            OrdenServicio::create([
                                'nroOrden' => $os[0],
                                'empresaOrden' => $os[1],
                                'temaOrden' => $os[2],
                                'fecIniOrden' => $fechaManual != 1 ? date('Y-m-d', strtotime($fechaInicio)) : date('Y-m-d', strtotime($os[3])),
                                'fecFinOrden' => $fechaManual != 1 ? date('Y-m-d', strtotime($fechaFinal)) : date('Y-m-d', strtotime($os[4])),
                                'undMedidaOrden' => 'HORAS',
                                'unidadesOrden' => $os[6],
                                "unidadesOrdenTotal" => $os[6],
                                'valorOrdenUnit' => $os[7],
                                'valorOrdenTotal' => $os[8],
                                'gestorSolicitado' => $gestor,
                                'obsOrden' => $observacion,
                                'participantes' => $os[5],
                                'nroOrdenPadre' => $os[0],

                                'fecSubida' => $fechaRegistro,
                                'idUserSube' => Auth::user()->id,
                                'idTipo' => 1,
                                'idUserAsigna' => null,
                                'idUserAsignadoa' => null,
                                'idCliente' => $idCliente,
                                'idEstadoReserva' => 1
                            ]);
                        }
                    }
                }
                $data = array(
                    'mensaje' => 'ok'
                );
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            } catch (\Throwable $e) {
                DB::rollback();
                throw $e;
            }
        }

        return response()->json($data, 200);
    }

    function validarVacios()
    {

    }

    public function storeHandbook(Request $request)
    {
        //
        $json = $request->input('json', null);
        $params = json_decode($json);

        if (isset($json)) {
            if (isset($params->nombre)) {
                $name = $params->nombre;
            } else {
                $name = null;
            }
            if (isset($params->dominio)) {
                $dom = $params->dominio;
            } else {
                $dom = null;
            }
            if (isset($params->tiempo)) {
                $time = $params->tiempo;
            } else {
                $time = null;
            }
            if (isset($params->getToken)) {
                $getToken = $params->getToken;
            } else {
                $getToken = false;
            }
        }

        if ($getToken && !empty($name) && !empty($dom) && !empty($time)) {
            $data = array(
                'mensaje' => 'ok'
            );
        } else {
            $data = array(
                'mensaje' => 'El campo x es requerido'
            );
        }

        return response()->json($data, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
