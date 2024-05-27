<?php

namespace App\Http\Controllers;

use App\Models\TiposDocumentos;
use Illuminate\Http\Request;

class InvitadoController extends Controller
{
    public function index()
    {
        return view('invitado.search');
    }
}
