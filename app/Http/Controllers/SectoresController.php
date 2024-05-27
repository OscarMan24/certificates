<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class SectoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('sectores.index'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $title = "Sectores";
        return view('panel.sectores.index', compact('title'));
    }

    
}
