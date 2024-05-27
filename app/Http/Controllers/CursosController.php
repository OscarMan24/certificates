<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class CursosController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('cliente.index'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $title = "Cursos";
        return view('panel.cursos.index', compact('title'));
    }
}
