<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cursos;
use App\Models\Aliados;
use App\Models\Asesores;
use App\Models\Certificados;
use App\Models\Instructores;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function obtenerDataCertificados(Request $request)
    {
        $cursos = Cursos::select('name', 'id', 'color')
            ->where([
                ['deleted', 0], ['status', 1]
            ])->get();


        $hoy = Carbon::now();
        $mesActual = $hoy->month;
        $añoActual = $hoy->year;

        $labelsCertificados = [];
        $contadorCertificados = [];
        $coloresCertificados = [];
        $encontrado = false;
        foreach ($cursos as $item) {
            $contador = Certificados::where('curso_id', $item->id)->whereMonth('created_at', $mesActual)
                ->whereYear('created_At', $añoActual)->count();
            if ($contador > 0) {
                $labelsCertificados[]   = $item->name;
                $contadorCertificados[] = $contador;
                $coloresCertificados[]  = $item->color;
                $encontrado             = true;
            }
        }

        $chartData = [
            'labels' => $labelsCertificados,
            'datasets' => [
                [
                    'data' => $contadorCertificados,
                    'backgroundColor' => $coloresCertificados,
                    'borderWidth'   => 0.5
                ]
            ]
        ];
        $response = [
            'data' => $chartData,
            'encontrado' => $encontrado,
        ];

        return response()->json($response);
    }

    public function obtenerDataInstructores(Request $request)
    {
        $instructores = Instructores::where([
            ['deleted', 0], ['status', 1]
        ])->get();

        $labelsInstructores = [];
        $contadorInstructores = [];
        foreach ($instructores as $item) {
            $contador = Certificados::where('instructor_id', $item->id)->count();
            if ($contador > 0) {
                $labelsInstructores[] = $item->name . ' ' . $item->last_name;
                $contadorInstructores[] = $contador;
            }
        }

        $chartData = [
            'labels' => $labelsInstructores,
            'datasets' => [
                [
                    'data' => $contadorInstructores,
                    'backgroundColor' => [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4CAF50',
                        '#673AB7',
                        '#FF9800'
                    ],
                    'borderWidth'   => 0.5
                ]
            ]
        ];

        $response = [
            'data' => $chartData
        ];

        return response()->json($response);
    }

    public function obtenerDataAsesores(Request $request)
    {
        $hoy = Carbon::now();
        $mesActual = $hoy->month;
        $añoActual = $hoy->year;
        $asesores = Asesores::where([
            ['deleted', 0], ['status', 1]
        ])->get();

        $labels = [];
        $contadores = [];
        $encontrado = false;
        foreach ($asesores as $item) {
            $contador = Certificados::where('asesor_id', $item->id)->whereMonth('created_at', $mesActual)
                ->whereYear('created_At', $añoActual)->count();
            if ($contador > 0) {
                $labels[] = $item->name . ' ' . $item->last_name;
                $contadores[] = $contador;
                $encontrado = true;
            }
        }

        $chartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $contadores,
                    'backgroundColor' => [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4CAF50',
                        '#673AB7',
                        '#FF9800'
                    ]
                ]
            ]
        ];

        $response = [
            'data' => $chartData,
            'encontrado' => $encontrado,
        ];
        return response()->json($response);
    }
}
