<?php

namespace App\Http\Livewire\Panel\Reporte;

use App\Models\Cursos;
use Livewire\Component;
use App\Models\Certificados;

class ReporteLivewire extends Component
{
    public $startDate;
    public $endDate;
    public $searchCurso;
    public $organizarPor = 'total';
    public $chartData = [
            'labels' => [],
            'data' => [],
        ];
    public $certificadosTotales = [];
    protected $queryString = [
        'startDate' => ['except' => '', 'as' => 'startDate'],
        'endDate' => ['except' => '', 'as' => 'endDate'],
        'searchCurso' => ['except' => '', 'as' => 'curso'],
        'organizarPor' => ['except' => '', 'as' => 'organizarPor'],
    ];

    public function mount()
    {
        $this->generateChartData();
    }

    public function render()
    {
        return view('livewire.panel.reporte.reporte-livewire');
    }

    public function generateChartData()
    {
        $cursoBuscar = $this->searchCurso;
        $dateStart = $this->startDate;
        $dateEnd = $this->endDate;

        $certificados = Certificados::select(
            'id',
            'curso_id',
            'created_at',
            'deleted',
            'course_name'
        )
        ->selectRaw("DATE_FORMAT(created_at, '%d/%m/%y') AS createdOnFormatted")
        ->where('deleted', 0)
        ->when(!empty($cursoBuscar), function ($query) use ($cursoBuscar) {
            return $query->where('curso_id', $cursoBuscar);
        })
        ->when(!empty($dateStart), function ($query) use ($dateStart) {
            return $query->whereDate('created_at', '>=', $dateStart);
        })
        ->when(!empty($dateEnd), function ($query) use ($dateEnd) {
            return $query->whereDate('created_at', '<=', $dateEnd);
        })
        ->orderBy('createdOnFormatted')
        ->get();

        // Inicializar un array para almacenar los datos del gráfico
        $chartData = [
            'labels' => [],
            'datasets' => [],
        ];

        $certificadosTotales = [];

        $type = '';
        switch ($this->organizarPor) {
            case 'total':
                $type = 'pie';
                $agrupados = $certificados->groupBy('curso_id')->map(function ($group) {
                    return $group->count();
                });

                $contador = [];
                $backgroundColor = [];
                foreach ($agrupados as $cursoId => $count) {
                    $curso = $this->Cursos->find($cursoId);

                    // Agregar cada conjunto de datos con su etiqueta correspondiente
                    $chartData['labels'][]  = $curso->name;
                    $backgroundColor[]      = $curso->color;
                    $contador[]             = $count;
                    
                    $certificadosTotales[] = [
                        'name' =>  $curso->name,
                        'count' => $count
                    ];
                }
                $chartData['datasets'][] = [
                    'data' => $contador, // En un gráfico de tipo 'pie', cada dataset debe tener un solo valor
                    'backgroundColor' => $backgroundColor,
                ];
                break;
            case 'fecha':
                $type = 'bar';

                $agrupados = $certificados->groupBy('createdOnFormatted');
                foreach ($agrupados as $fecha => $value) {
                    $chartData['labels'][] = $fecha;
                }

                $agrupados2 = $certificados->groupBy('curso_id');

                foreach ($agrupados2 as $cursoId => $group) {
                    $curso = $this->Cursos->find($cursoId);
                    $data = [];

                    foreach ($agrupados as $key => $value) {                    
                        $data[] = $group->where('createdOnFormatted', $key)->count();                                             
                    }

                    $chartData['datasets'][] = [
                        'label' => $curso->name ?? $group->course_name ,
                        'data' => $data,
                        'backgroundColor' => $curso->color,
                    ];


                    $certificadosTotales[] = [
                        'name' =>  $curso->name,
                        'count' =>  $group->count(),
                    ];
                }
                break;            
            default:
                # code...
                break;
        }

        $chartData['type'] = $type;

        // Asignar los datos al chartData del componente Livewire
        $this->chartData = $chartData;
        $this->certificadosTotales = $certificadosTotales;
    }


    public function getCursosProperty()
    {
        return Cursos::where([
            ['status', 1], ['deleted', 0]
        ])->orderBy('consecutive')->get();
    }
}
