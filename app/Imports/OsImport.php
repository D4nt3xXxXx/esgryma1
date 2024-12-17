<?php

namespace App\Imports;

use App\OrdenServicio\OrdenServicio;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class OsImport implements ToCollection
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $row)
    {
        //
        return new OrdenServicio([
            //
            'nroOrden'     => $row[0],
            'nroOrdenPadre'    => $row[1],
            'temaOrden' => $row[2],
        ]);
    }
}
