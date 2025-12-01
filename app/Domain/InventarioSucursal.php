<?php

namespace App\Domain;

class InventarioSucursal
{
    private Sucursal $sucursal;
    private Medicamento $medicamento;
    private int $stockMinimo;
    private int $stockMaximo;
    private int $stockActual;
    private float $precioActual;

    public function __construct(Sucursal $sucursal, Medicamento $medicamento, int $stockMinimo, int $stockMaximo, 
    int $stockActual, float $precioActual) 
    {
        $this->sucursal = $sucursal;
        $this->medicamento = $medicamento;
        $this->stockMinimo = $stockMinimo;
        $this->stockMaximo = $stockMaximo;
        $this->stockActual = $stockActual;
        $this->precioActual = $precioActual;
    }

    public function verificarStock(): bool
    {
        return $this->stockActual > 0;
    }
    
    public function descontarMedicamento($cantidad): void
    {
        $this->stockActual -= $cantidad;
    }


    public function devolverMedicamento($cantidad): void
    {
        $this->stockActual += $cantidad;
    }

    public function obtenerSucursal(): Sucursal { 
        return $this->sucursal; 
    }

    //Aqui tambien get me dice
    public function obtenerMedicamento(): Medicamento { 
        return $this->medicamento; 
    }

    public function obtenerPrecio(): int { 
        return $this->precioActual; 
    }

    public function getStockMinimo(): int { 
        return $this->stockMinimo; 
    }

    public function getStockMaximo(): int { 
        return $this->stockMaximo; 
    }
    //Carlos me dice get no olvidar
    public function obtenerStock(): int { 
        return $this->stockActual; 
    }

    public function setSucursal(Sucursal $sucursal): void { 
        $this->sucursal = $sucursal; 
    }

    public function setMedicamento(Medicamento $medicamento): void { 
        $this->medicamento = $medicamento; 
    }

    public function setStockMinimo(int $stockMinimo): void { 
        $this->stockMinimo = $stockMinimo; 
    }

    public function setStockMaximo(int $stockMaximo): void { 
        $this->stockMaximo = $stockMaximo; 
    }

    public function setStockActual(int $stockActual): void { 
        $this->stockActual = $stockActual; 
    }

    public function setPrecioActual(int $precioActual): void { 
        $this->precioActual = $precioActual; 
    }

    public function toArray(): array
    {
        return [
            'sucursal'      => $this->sucursal->toArray(),
            'medicamento'   => $this->medicamento->toArray(),
            'stockMinimo'   => $this->stockMinimo,
            'stockMaximo'   => $this->stockMaximo,
            'stockActual'   => $this->stockActual,
            'precioActual'  => $this->precioActual,
        ];
    }

}