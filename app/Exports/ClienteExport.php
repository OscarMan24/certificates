<?php

namespace App\Exports;

use App\Models\Clientes;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClienteExport implements FromArray, ShouldAutoSize, WithStyles, WithColumnWidths
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
            'Id', 'Nombre', 'Apellido', 'Tipo documento', 'Documento', 'Telefono', 'Correo', 'Genero', 'Fecha de nacimiento', 'Pais de nacimiento', 'Nivel de educacion', 
            'Area de trabajo', 'Cargo actual', 'Aliado', 'Asesor', 'Estado', 'Creado por', 'Fecha de creado', 'Editado por', 'Fecha de editado'
        ];

        $usuarios = Clientes::where([
            ['deleted', 0]
        ])->when($this->tipo == 2, function ($query, $search) {
            $query->where('status', 1);
        })->when($this->tipo == 3, function ($query, $search) {
            $query->where('status', 0);
        })->with(['creadoPor', 'editadoPor'])->get();

        if (!empty($usuarios)) {
            foreach ($usuarios as $item) {
                $array[] = array(
                    'Id' => $item->id,
                    'Nombre' => $item->name,
                    'Apellido' => $item->last_name,
                    'Tipo documento' => $item->type_document,
                    'Documento' => $item->document,
                    'Telefono' => $item->phone,
                    'Correo' => $item->email,
                    'Genero'    => $item->gender,
                    'Fecha de nacimiento' => $item->birthdate,
                    'Pais de nacimiento' => $item->country_of_birth,
                    'Nivel de educacion' => $item->education_level,
                    'Area de trabajo'   => $item->work_area,
                    'Cargo actual'  => $item->actual_charge,
                    'Aliado' => $item->aliado->name . ' ' . $item->aliado->last_name,
                    'Asesor' => $item->asesor->name . ' ' . $item->asesor->last_name,
                    'Estado' => $item->status == 1 ? 'Activo' : 'Desactivado',
                    'Creado por' => isset($item->creadoPor) ? $item->creadoPor->name . ' ' . $item->creadoPor->last_name : '', 
                    'Fecha de creado' => $item->created_at, 
                    'Editado por' => isset($item->editadoPor) ? $item->editadoPor->name  . ' ' . $item->editadoPor->last_name : 'No', 
                    'Fecha de editado' =>  isset($item->editadoPor) ? $item->updated_at : 'No'
                );
            }
        }

        return [$array];
    }
}
