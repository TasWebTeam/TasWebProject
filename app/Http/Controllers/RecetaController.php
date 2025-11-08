<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RecetaController extends Controller
{
    public function subirRecetaView(){
        return view('tas.subir_receta');
    }
}
