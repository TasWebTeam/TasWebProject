<?php

namespace App\Domain;

use App\Repositories\ConsultarRepository;
use App\Repositories\ActualizarRepository;
use App\Services\RutaOpenStreetMapService;
use App\Services\SucursalService;
use InvalidArgumentException;

class DetalleReceta
{
    private Medicamento $medicamento;
    private int $cantidad;
    private float $precio;
    private array $lineasSurtido = [];
    private ActualizarRepository $actualizarRepository;

    public function __construct(
        ?Medicamento $medicamento = null,
        int $cantidad = 0,
        float $precio = 0.0,
        array $lineasSurtido = []
    ){
        $this->medicamento = $medicamento;
        $this->cantidad = $cantidad;
        $this->precio = $precio;
        $this->setLineasSurtido($lineasSurtido);
    }

    public function obtenerSubtotal(): float 
    {
        return $this->cantidad * $this->precio;
    }

    public function registrarSurtido(Sucursal $sucursal, int $cantidadSurtida, String $estado): void
    {
        $linea = new LineaSurtido(
            $sucursal,
            $estado,
            $cantidadSurtida
        );
        $this->agregarLineaSurtido($linea);
    }

    public function getMedicamento(){
        return $this->medicamento;
    }

    public function getCantidad(): int
    {
        return $this->cantidad;
    }

    public function getPrecio(): float
    {
        return $this->precio;
    }

    public function getLineasSurtido(): array
    {
        return $this->lineasSurtido;
    }

    public function setCantidad(int $cantidad): void
    {
        $this->cantidad = $cantidad;
    }

    public function setPrecio(float $precio): void
    {
        $this->precio = $precio;
    }

    public function setLineasSurtido(array $lineas): void
    {
        foreach ($lineas as $ls) {
            if (!$ls instanceof LineaSurtido) {
                throw new InvalidArgumentException("Cada elemento debe ser instancia de LineaSurtido");
            }
        }

        $this->lineasSurtido = $lineas;
    }

    public function agregarLineaSurtido(LineaSurtido $linea): void
    {
        $this->lineasSurtido[] = $linea;
    }
}