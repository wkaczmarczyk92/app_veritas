<?php

namespace App\Exports;

use App\Models\UserHasBonus;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class PayoutRequestsExport
extends DefaultValueBinder
implements
    FromArray,
    WithHeadings,
    ShouldAutoSize,
    WithStyles,
    WithCustomValueBinder
{
    public $array = [];
    public $last_x_cell = 0;

    public $alert_rows = [];

    public function __construct($arr, $last_x_cell, $alert_rows = [])
    {
        $this->array = $arr;
        $this->last_x_cell = $last_x_cell;
        $this->alert_rows = $alert_rows;
    }

    public function array(): array
    {
        return $this->array;

        return UserHasBonus::with('user')
            ->get();
    }

    public function bindValue(Cell $cell, $value)
    {
        if ($cell->getColumn() == 'E') {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);
            return true;
        }

        if ($cell->getColumn() == 'B') {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);
            return true;
        }

        return parent::bindValue($cell, $value);
    }

    public function headings(): array
    {
        return [
            '#',
            'PESEL',
            'Imię',
            'Nazwisko',
            'Nr telefonu',
            'Wartość bonusu',
            'Nazwa spółki',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        if ($this->last_x_cell > 0) {
            $cell_range = "A1:G{$this->last_x_cell}";
            $sheet->getStyle($cell_range)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ]
            ]);
        }

        if (!empty($this->alert_rows)) {
            foreach ($this->alert_rows as $row) {
                $cell_range = "A{$row}:G{$row}";
                $sheet->getStyle($cell_range)->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'ffdd99',
                        ],
                    ],
                ]);
            }
        }

        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}
