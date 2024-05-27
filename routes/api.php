<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['api'])->group(
    function () {
        Route::get('checkCertificados', [ApiController::class, 'obtenerDataCertificados'])
            ->name('check.certificados');

        Route::get('checkInstructores', [ApiController::class, 'obtenerDataInstructores'])
            ->name('check.Instructores');

        Route::get('checkAsesores', [ApiController::class, 'obtenerDataAsesores'])
            ->name('check.asesores');
    }
);
