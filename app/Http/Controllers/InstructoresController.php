<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class InstructoresController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('instructor.index'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $title = "Instructores";
        return view('panel.instructores.index', compact('title'));
    }
}
