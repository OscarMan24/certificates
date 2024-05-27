<?php

namespace App\Exports;

use App\Models\Asesores;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AsesorExport implements FromArray, ShouldAutoSize, WithStyles, WithColumnWidths
{
    use Exportable;
    protected $fecha_desde, $fecha_hasta;

    public function __construct($desde, $hasta)
    {
        $this->fecha_desde = $desde;
        $this->fecha_hasta = $hasta;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1   => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 3,
        ];
    }

    public function array(): array
    {
        $array = [];
        $array[] =  [
            'Id', 'Tipo Documento', 'Documento', 'Nombre asesor', 'Telefono', 'Correo', 'Estado'
        ];

        $aliados = Asesores::where('deleted', 0)
            ->when(!empty($this->fecha_desde), function ($query, $search) {
                $query->whereDate('created_at', '>=', $this->fecha_desde);
            })->when(!empty($this->fecha_hasta), function ($query, $search) {
                $query->whereDate('created_at', '<=', $this->fecha_hasta);
            })->get();

        if (!empty($aliados)) {
            foreach ($aliados as $item) {
                $array[] = array(
                    'Id' => $item->id,
                    'Tipo Documento' => $item->type_document,
                    'Documento' => $item->document,
                    'Nombre aliado' => $item->name . ' ' . $item->last_name,
                    'Telefono' => $item->phone,
                    'Correo' => $item->email,
                    'Estado' => $item->status == 1 ? 'Activo' : 'Desactivado'
                );
            }
        }

        return [$array];
    }
}
