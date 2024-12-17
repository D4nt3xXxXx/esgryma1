<?php
/**
 * Created by PhpStorm.
 * User: breynerperez
 * Date: 21/02/2020
 * Time: 10:50 PM
 */

namespace App\modelos;


use Colors\RandomColor;
use Illuminate\Support\Facades\DB;

class consultas
{
    static function listarNovedades()
    {
        return DB::select("select idTipoNovedad as id,tipoNovedad as text,validacbox as rolvalidar from tipo_novedad");
    }

    static function configApp($parametro, $all = false)
    {
        if ($all)
            return DB::select("select idConfig as id,atributo,valor from configapp ");

        return DB::select("select idConfig as id,atributo,valor from configapp where atributo='$parametro'");
    }

    static function consultarEstadoOT()
    {
        return DB::select("select idEstado,codEstado,nomEstado,entidad from estados where entidad='OT' order by nomEstado asc;");
    }

    static function consultarCategoria()
    {
        return DB::select("select c.idCategoria as id, c.categoriaActividad as nombre from categoria c;");
    }

    static function consultarTema()
    {
        return DB::select("select t.idTema as id, t.temaActividad as nombre from tema t;");
    }

    static function consultarTipo()
    {
        return DB::select("select t.idTipo as id, t.tipoActividad as nombre,t.prefijo from tipo t;");
    }

    static function consultarGrupo()
    {
        return DB::select("select g.idGrupo as id, g.nomGrupo as nombre from grupo g;");
    }

    static function consultarReporte($consulta, $fechaInicio, $fechaFin)
    {
        $datos = [];
        $view = "";
        $datosChart = [];
        $tempSeries = [];
        $sql = $consulta["sql"];
        $groupBy = $consulta["groupBy"];
        $filtro = $consulta["filtro"];
        $esGrafico = $consulta["grafico"];
        $ejeX = $consulta["ejeX"];
        $ejeY = $consulta["ejeY"];
        $nombreSerie = $consulta["series"] . "";
        $query = $sql;
        $categorias = [];

        if (!empty($filtro))
            $query = $query . " where " . $filtro . " between '$fechaInicio' and '$fechaFin'";
        if (!empty($groupBy))
            $query = $query . " group by " . $groupBy;

        $datos = DB::select($query);

        if ($esGrafico == 1) {
            $datasets = [];
            $tempSeries = collect($datos)->groupBy("$nombreSerie");
            if (!empty($ejeY)) {
                if (!empty($nombreSerie)) {
                    $colores = RandomColor::many(count($tempSeries), array('luminosity' => 'light'));
                    array_push($datosChart, array("labels" => [], "datasets" => []));
                    $cont = 0;
                    foreach ($tempSeries as $index => $item) {
                        array_push($datasets, array("label" => $index, "data" => [], /*"fill" => false,*/ /*"pointBackgroundColor" => $colores[$cont],*/ "backgroundColor" => $colores[$cont]));
                        foreach ($item as $index2 => $item2) {
                            array_push($datasets[$cont]["data"], $item2->$ejeY);
                        }
                        $cont++;
                    }
                } else {
                    if (!empty($ejeY)) {
                        $colores = RandomColor::many(count($tempSeries), array('luminosity' => 'light'));
                        array_push($datosChart, array("labels" => $nombreSerie, "datasets" => array("label" => [], "data" => [])));
                        $cont = 0;
                        array_push($datasets, array("label" => $nombreSerie, "data" => []));
                        $tempData = [];
                        foreach ($datos as $index => $item) {
                            array_push($tempData, $item->$ejeY);
                            $cont++;
                        }
                        $datasets[0]["data"] = $tempData;
                    }
                }
                if (!empty($ejeX)) {
                    $tempCategorias = $tempSeries = collect($datos)->groupBy($ejeX);;
                    foreach ($tempCategorias as $index => $item) {
                        array_push($categorias, "$index");
                    }
                }
                $datosChart[0]["labels"] = $categorias;
                $datosChart[0]["datasets"] = $datasets;
            }
        }
        return array("datosChart" => $datosChart, "datos" => $datos, "query" => $query, "categorias" => $categorias);
    }

    static function datosSeries($array, $columna)
    {
        $tempDatos = [];
        foreach ($array as $item) {
            array_push($tempDatos, $item->$columna);
        }
        return $tempDatos;
    }

    static function consultarTablaReportes($categoria, $subcategoria)
    {
        $datos = DB::table("baseReportes");

        if (!empty($categoria))
            $datos->whereIn("idCategoria", $categoria);
        if (!empty($subcategoria))
            $datos->whereIn("idSubCategoria", $subcategoria);

        $datos->orderBy("idCategoria");

        return $datos->get();
    }
}
