<?php
/**
 * Created by PhpStorm.
 * User: breynerperez
 * Date: 19/02/2020
 * Time: 10:19 PM
 */

namespace App\modelos;


use Illuminate\Support\Facades\DB;

class salones
{
    static function listarEventosSalones($idSalon = null)
    {
        if (empty($idSalon))
            return DB::select("SELECT * FROM view_ot_detalle where idSalon is not null;");
        else
            return DB::select("SELECT * FROM view_ot_detalle where idSalon=$idSalon;");
    }
}
