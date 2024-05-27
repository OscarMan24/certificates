<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('users.index'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $title = 'Usuarios';
        return view('panel.usuarios.index', compact('title'));
    }
    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('inicio');
        } else {
            abort(404);
        }
    }
}
