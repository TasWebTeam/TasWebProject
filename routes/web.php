<?php

use App\Http\Controllers\TasController;
use App\Http\Controllers\ProcesarRecetaController;
use App\Http\Controllers\EmpleadoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TasController::class, 'tas_inicioView'])->name('tas_inicioView');

Route::get('/login', [TasController::class, 'tas_loginView'])
    ->middleware('redirigir.si.autenticado')
    ->name('tas_loginView');

Route::get('/registro', [TasController::class, 'tas_registroView'])
    ->middleware('redirigir.si.autenticado')
    ->name('tas_registroView');

Route::post('/login', [TasController::class, 'tas_inicioSesion'])->name('tas_inicioSesion');
Route::post('/registro', [TasController::class, 'tas_crearCuenta'])->name('tas_crearCuenta');
Route::post('/registro/validar-cliente', [TasController::class, 'validarPasoCliente'])
    ->name('tas.validarPasoCliente');

Route::get('/subir_receta', [TasController::class, 'tas_subirRecetaView'])
    ->middleware('verificar.sesion')
    ->name('tas_subirRecetaView');

Route::get('/metodo_pago', [TasController::class, 'tas_metodoPagoView'])
    ->middleware('verificar.sesion')
    ->name('tas_metodoPagoView');

Route::post('/metodo-pago/actualizar', [TasController::class, 'tas_actualizarTarjeta'])
    ->name('tas_actualizarTarjeta');

Route::post('/logout', [TasController::class, 'logout'])
    ->middleware('verificar.sesion')
    ->name('logout');

    
Route::post('/TESTING', [ProcesarRecetaController::class, 'TESTING'])
    ->name('TESTING');

Route::view('/acerca', 'tas.acerca')->name('acerca');
Route::view('/servicio', 'tas.servicio')->name('servicio');

Route::middleware(['verificar.sesion', 'solo.empleado'])->group(function () {

    Route::get('/empleado/recetas', [EmpleadoController::class, 'recetas'])
        ->name('empleado_recetas');

    Route::get('/empleado/recetas-expiradas', [EmpleadoController::class, 'recetasExpiradas'])
        ->name('empleado_recetas_expiradas');
});

