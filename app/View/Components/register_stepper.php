<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class register_stepper extends Component
{
    public function __construct()
    {
        
    }

    public function render(): View|Closure|string
    {
        return view('components.register_stepper');
    }
}
