<?php

namespace App\Http\Controllers;

use App\Models\TiposDocumentos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class TiposDocumentosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('tipo.documento.index'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $title = "Documentos identidad";
        return view('panel.tiposdocumentos.index', compact('title'));
    }
}
