<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class mapa_farmacias extends Component
{
    public $sucursales;

    public function __construct($sucursales)
    {
        $this->sucursales = $sucursales;
    }
    
    public function render(): View|Closure|string
    {
        return view('components.mapa_farmacias');
    }
}
