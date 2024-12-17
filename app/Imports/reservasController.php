<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;

class reservasController implements ToCollection
{
    use Importable;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $row)
    {
        return collect([
            'registration_number' => $row[0],
            'brand' => $row[1],
            'model' => $row[2],
            'type' => $row[3],
            'fuel_type' => $row[4],
            'year' => $row[5],
            'doors' => $row[6],
            'is_active' => $row[7],
        ]);
    }
}
