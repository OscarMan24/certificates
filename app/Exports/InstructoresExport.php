<?php

namespace App\Exports;

use App\Models\Instructores;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InstructoresExport implements FromArray, ShouldAutoSize, WithStyles, WithColumnWidths
{
    use Exportable;
    protected $tipo;

    public function __construct($tipo)
    {
        $this->tipo = $tipo;
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
            'Id', 'Nombre', 'Apellido', 'Tipo documento', 'Documento', 'Telefono', 'Correo', 'Resolucion SO', 'Observacion', 'Firma', 'Estado'
        ];

        $instructores = Instructores::where([
            ['deleted', 0]
        ])->when($this->tipo == 2, function ($query, $search) {
            $query->where('status', 1);
        })->when($this->tipo == 3, function ($query, $search) {
            $query->where('status', 0);
        })->get();

        if (!empty($instructores)) {
            foreach ($instructores as $item) {
                $array[] = array(
                    'Id' => $item->id,
                    'Nombre' => $item->name,
                    'Apellido' => $item->last_name,
                    'Tipo documento' => $item->type_document,
                    'Documento' => $item->document,
                    'Telefono' => $item->phone,
                    'Correo' => $item->email,
                    'Resolucion SO' => $item->resolucion_so,
                    'Observacion' => $item->observacion,
                    'Firma' =>  asset('/storage/instructores/' . $item->signature),
                    'Estado' => $item->status == 1 ? 'Activo' : 'Desactivado'
                );
            }
        }

        return [$array];
    }
}
