<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CursosController;
use App\Http\Controllers\AliadosController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\AsesoresController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\CertificadosController;
use App\Http\Controllers\InstructoresController;
use App\Http\Controllers\InvitadoController;
use App\Http\Controllers\TiposDocumentosController;
use App\Http\Controllers\RepresentanteLegalController;
use App\Http\Controllers\SectoresController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/', [HomeController::class, 'index'])->name('inicio');
Route::get('/login', [HomeController::class, 'login'])->name('iniciar');
Route::post('/login', [HomeController::class, 'adminLogin'])->name('iniciar.post');

Route::get('/commands/{token}/{id}', [HomeController::class, 'commandos'])->name('comando_artisan');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/cerrar-sesion', [UserController::class, 'logout'])->name('logout');
    Route::prefix('panel')->group(function () {
        Route::get('/usuarios', [UserController::class, 'index'])->name('index.usuarios');
        Route::get('/roles', [HomeController::class, 'indexroles'])->name('index.roles');
        Route::get('/documentos-identidad', [TiposDocumentosController::class, 'index'])->name('index.tipos.documentos');
        Route::get('/aliados', [AliadosController::class, 'index'])->name('index.aliados');
        Route::get('/asesores', [AsesoresController::class, 'index'])->name('index.asesores');
        Route::get('/clientes', [ClientesController::class, 'index'])->name('index.clientes');
        Route::get('/instructores', [InstructoresController::class, 'index'])->name('index.instructores');
        Route::get('/cursos', [CursosController::class, 'index'])->name('index.cursos');
        Route::get('/certificados', [CertificadosController::class, 'index'])->name('index.certificados');
        Route::get('/configuracion', [HomeController::class, 'indexSetting'])->name('index.configuracion');
        Route::get('/horarios', [HorarioController::class, 'index'])->name('index.horarios');
        Route::get('/representantes-legales', [RepresentanteLegalController::class, 'index'])->name('index.respresentantes.legales');

        Route::get('/dashboard', [HomeController::class, 'indexdashboard'])->name('index.dashboard');
        Route::get('/sectores', [SectoresController::class, 'index'])->name('index.sectores');
        Route::get('/reportes', [HomeController::class, 'indexReportes'])->name('index.reportes');
    });

    
});


Route::get('/exportarPdfs/{id}', [HomeController::class, 'exportarPdf'])->name('exportar.pdfs');
Route::get('/showPdfs/{id}/{name}', [HomeController::class, 'showPdf'])->name('show.pdfs');
Route::get('/verificar-certificados', [InvitadoController::class, 'index'])->name('verificar.certificado');
