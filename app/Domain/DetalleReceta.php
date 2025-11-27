<?php

namespace App\Domain;
use InvalidArgumentException;

class DetalleReceta
{
    private int $cantidad;
    private float $precio;
    private Medicamento $medicamento;
    private array $lineasSurtido = [];

    public function __construct(
        int $cantidad = 0,
        float $precio = 0.0,
        ?Medicamento $medicamento = null,
        array $lineasSurtido = []
    ){
        $this->cantidad = $cantidad;
        $this->precio = $precio;
        $this->medicamento = $medicamento;
        $this->setLineasSurtido($lineasSurtido);
    }

    public function obtenerSubtotal(): float
    {
        return $this->cantidad * $this->precio;
    }

    public function procesar(Sucursal $sucursal): void{
        $seAbastece = false;
        
        while(!$seAbastece){
            $cantidadRequerida= $this->cantidad;
            $cantidadObtenida = $sucursal->verificarDisponibilidad($this->cantidad, $this->medicamento);
            if($cantidadObtenida > 0){
                $linea = new LineaSurtido($cantidadObtenida, $this->precio);
                $this->agregarLineaSurtido($linea);
                $cantidadRequerida -= $cantidadObtenida;
            }
            if($cantidadRequerida == 0){
                $seAbastece = true;
            }
            else{
                $coordenadas = $this->obtenerSucursalCercana($this->sucursal);
                //Buscar sucursal cercana
                //Abastecer desde sucursal cercana
            }
        }
    }

    public function abastecer(): void{

    }

    public function obtenerSucursalCercana(Sucursal $suc): void{
        
    }

    public function realizarDevolucion(): void{
        
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