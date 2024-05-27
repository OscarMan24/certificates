<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class HorarioController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('horario.index'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $title = "Horarios";
        return view('panel.horarios.index', compact('title'));
    }
}
