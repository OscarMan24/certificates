<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Certificados;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CertificadosExport implements FromArray, ShouldAutoSize, WithStyles, WithColumnWidths
{
    use Exportable;
    protected $fecha_desde, $fecha_hasta, $curso;

    public function __construct($desde, $hasta, $curso)
    {
        $this->fecha_desde = $desde;
        $this->fecha_hasta = $hasta;
        $this->curso = $curso;
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
        return [];
    }

    public function array(): array
    {
       
        $array = [];
        //Formato viejo
        /* $array[] =  [
            'Consecutivo', 'Curso', 'Cliente', 'Documento Cliente', 'Instructor',
            'Aliado', 'Arl', 'Representante Legal', 'Asesor', 'Tiempo', 'Desde', 'Hasta', 'Estado', 'Creado por', 'Fecha', 'Hora'
        ];*/

        $array[] =  [
            'tipo_documento', 'documento', 'primer nombre', 'segundo nombre', 'primer apellido', 'segundo apellido', 'genero', 'pais nacimiento', 
            'fecha nacimiento', 'nivel educativo', 'area de trabajo', 'cargo actual', 'sector', 'empleador', 'arl', 'Curso', 'Creado por', 'Fecha'
        ];

        $Certificados = Certificados::where('deleted', 0)
            ->when(!empty($this->curso) && $this->curso != 'Todos', function ($query, $curso) {
                $query->where('curso_id',  $this->curso);
            })->when(!empty($this->fecha_desde), function ($query, $fecha_desde) {
                $query->whereDate('created_at', '>=', $this->fecha_desde);
            })->when(!empty($this->fecha_hasta), function ($query, $fecha_hasta) {
                $query->whereDate('created_at', '<=', $this->fecha_hasta);
            })->get();

        if (!empty($Certificados)) {
            foreach ($Certificados as $item) {
                $array[] = array(
                    'tipo_documento'    => $item->cliente->type_document,
                    'documento'         => $item->cliente->document,
                    'primer nombre'     => $item->cliente->name,
                    'segundo nombre'    => $item->cliente->second_name,
                    'primer apellido'   => $item->cliente->last_name,
                    'segundo apellido'  => $item->cliente->second_last_name,
                    'genero'            => $item->cliente->gender,
                    'pais nacimiento'   => $item->cliente->country_of_birth,
                    'fecha nacimiento'  => $item->cliente->birthdate,
                    'nivel educativo'   => $item->cliente->education_level,
                    'area de trabajo'   => $item->cliente->work_area,
                    'cargo actual'      => $item->cliente->actual_charge,
                    'sector'            => $item->aliado->sector->name,
                    'empleador'         => $item->aliado->name,
                    'arl'               => $item->aliado->arl_name,
                    'Curso'             => $item->course_name,
                    'Creado por'        => $item->usuario->name . ' ' . $item->usuario->last_name,
                    'Fecha'             => $item->updated_at
                );


                //Formato viejo
                /*$array[] = array(
                    'Consecutivo' => $item->consecutive,
                    'Curso' => $item->course_name,
                    'Cliente' => $item->cliente->name . ' ' . $item->cliente->last_name,
                    'Documento Cliente' => $item->cliente->type_document . ' ' . $item->cliente->document,
                    'Instructor' =>  $item->instructor->name . ' ' . $item->instructor->last_name . ' - ' . $item->instructor->resolucion_so,
                    'Aliado' =>  $item->aliado->name . ' - ' . $item->aliado->type_document . ' ' . $item->aliado->document,
                    'Arl' => $item->aliado->arl_name,
                    'Representante Legal' => $item->representanteLegal->name . ' ' . $item->representanteLegal->last_name,
                    'Asesor' => $item->asesor->name . ' ' . $item->asesor->last_name,
                    'Tiempo' => $item->hora->timer . ' ' . $item->hora->type,
                    'Desde' => Carbon::create($item->initial_date)->locale('es')->isoFormat('DD MMMM YYYY'),
                    'Hasta' => Carbon::create($item->final_date)->locale('es')->isoFormat('DD MMMM YYYY'),
                    'Estado' => $item->active == 1 ? 'Vigente' : 'Vencido',
                    'Creado por' => $item->usuario->name . ' ' . $item->usuario->last_name,
                    'Fecha' => Carbon::create($item->updated_at)->locale('es')->format('d/m/Y'),
                    'Hora' => Carbon::create($item->updated_at)->locale('es')->format('H:i'),
                );*/
            }
        }

        return [$array];
    }
}
