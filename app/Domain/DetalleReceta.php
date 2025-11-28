<?php

namespace App\Domain;
use InvalidArgumentException;

class DetalleReceta
{
    private Medicamento $medicamento;
    private int $cantidad;
    private float $precio;
    private array $lineasSurtido = [];

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

    public function procesar(Sucursal $suc): void{
        $seAbastece = false;
        
        while(!$seAbastece){
            $cantidadRequerida= $this->cantidad;
            $cantObtenida = $suc->verificarDisponibilidad($this->cantidad, $this->medicamento);
            if($cantObtenida > 0){
                $linea = new LineaSurtido($cantObtenida, $this->precio);
                $this->agregarLineaSurtido($linea);
                $cantidadRequerida -= $cantObtenida;
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