<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class formulario_receta extends Component
{
    public function __construct()
    {
        
    }

    public function render(): View|Closure|string
    {
        return view('components.formulario-receta');
    }
}
