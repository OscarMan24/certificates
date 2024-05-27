<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class AsesoresController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('aliado.index'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $title = "Asesores";
        return view('panel.asesores.index', compact('title'));
    }
}
