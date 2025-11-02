<?php

use App\Http\Controllers\TasController;
use Illuminate\Support\Facades\Route;

Route::get('/',[TasController::class,'tas_inicioView']) -> name('tas_inicioView');
Route::get('/login',[TasController::class,'tas_loginView']) -> name('tas_loginView');
Route::get('/registro',[TasController::class,'tas_registroView']) -> name('tas_registroView');
Route::post('/login',[TasController::class,'tas_inicioSesion']) -> name('tas_inicioSesion');
Route::post('/registro',[TasController::class,'tas_crearCuenta']) -> name('tas_crearCuenta');