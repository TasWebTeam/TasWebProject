<?php

namespace App\Domain;

class Pago {
    private float $monto;

    public function __construct(float $monto = 0){
        $this->monto = $monto;
    }

    public function actualizarComision(float $comisionTotal): void{
        $this->monto = $comisionTotal;     
    }

    public function validarPago(string $numTarjeta): void{
    }
}