<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RepresentanteLegal;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class RepresentanteLegalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('representate.legal.index'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $title = "Representantes Legales";
        return view('panel.representantes.index', compact('title'));
    }
}
