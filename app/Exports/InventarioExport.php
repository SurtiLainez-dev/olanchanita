<?php

namespace App\Exports;

use App\Models\Inventario;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class InventarioExport implements FromCollection, WithHeadings, WithTitle
{
    protected $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return new Collection($this->data);
    }

    public function headings(): array
    {
        return [
            'Codigo',
            'Articulo',
            'Modelo',
            'Marca',
            'Familia',
            'Sub Familia',
            'Stock Actual en Tilk',
            'Stock en Fisico',
            'Stock Real',
        ];
    }

    public function title(): string
    {
        return 'Reportes de inventario '.date('d/m/Y');
    }

}
