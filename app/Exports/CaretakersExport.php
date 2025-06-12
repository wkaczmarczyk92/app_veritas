<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromArray;


class CaretakersExport implements FromArray
{
    private $_collection;
    private $_array;

    public function __construct($array)
    {
        $this->_array = $array;
    }


    public function collection()
    {
        return $this->_collection;
    }

    public function array(): array
    {
        return $this->_array;
    }
}
