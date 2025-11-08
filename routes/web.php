<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TasController;

Route::get('/', [TasController::class, 'tas_inicioView'])->name('tas_inicioView');

Route::get('/login', [TasController::class, 'tas_loginView'])
    ->middleware('redirigir.si.autenticado')
    ->name('tas_loginView');

Route::get('/registro', [TasController::class, 'tas_registroView'])
    ->middleware('redirigir.si.autenticado')
    ->name('tas_registroView');

Route::post('/login', [TasController::class, 'tas_inicioSesion'])->name('tas_inicioSesion');
Route::post('/registro', [TasController::class, 'tas_crearCuenta'])->name('tas_crearCuenta');

Route::get('/subir-receta', [TasController::class, 'tas_subirRecetaView'])
    ->middleware('verificar.sesion')
    ->name('tas_subirRecetaView');

Route::post('/logout', [TasController::class, 'logout'])
    ->middleware('verificar.sesion')
    ->name('logout');
