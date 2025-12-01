<?php

namespace App\Domain;

use App\Repositories\ConsultarRepository;
use App\Services\RutaOpenStreetMapService;
use App\Services\SucursalService;
use InvalidArgumentException;

class DetalleReceta
{
    private Medicamento $medicamento;
    private int $cantidad;
    private float $precio;
    private array $lineasSurtido = [];
    private ConsultarRepository $consultarRepo;

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

    public function procesar(Sucursal $sucursal, SucursalService $sucursalService): void{
        $seAbastece = false;
        $cantidadRequerida = $this->cantidad;
        $sucursalActual    = $sucursal;
        while (!$seAbastece) {

            $cantObtenida = $sucursalActual->verificarDisponibilidad(
                $cantidadRequerida,
                $this->medicamento
            );
            // cantObtenida -> 3
            if ($cantObtenida > 0) {
                $linea = new LineaSurtido(
                    $sucursalActual,
                    $this->precio,
                    $cantObtenida
                );
                $this->agregarLineaSurtido($linea);
                $cantidadRequerida -= $cantObtenida;
            }

            if ($cantidadRequerida <= 0) {
                $seAbastece = true;
                break;
            }

            $siguienteSucursal = $sucursalService->obtenerSucursalMasCercanaConStock(
                $sucursal,
                $this->medicamento,
                $cantidadRequerida
            );

            if ($siguienteSucursal === null) {
                $seAbastece = true; // o marcar como incompleto / lanzar excepción
            } else {
                $sucursalActual = $siguienteSucursal;
            }
        }
        dd($this->getLineasSurtido());
    }

    public function abastecer(): void{

    }

    public function obtenerSucursalCercana(Sucursal $suc): void{
        
    }

    public function realizarDevolucion(): void{
        //$consultarRepo = new ConsultarRepository();
        foreach ($this->getLineasSurtido() as $linea) {
            $linea->devolverASucursal($linea->getCantidad(),$this->getMedicamento()->getNombre()); //agregue nombre del medicamento
        }
    }

    public function getMedicamento(): ?Medicamento
    {
        return $this->medicamento;
    }

    public function setMedicamento(?Medicamento $medicamento): void
    {
        if ($medicamento !== null && !$medicamento instanceof Medicamento) {
            throw new InvalidArgumentException("El valor debe ser un objeto Medicamento o null.");
        }

        $this->medicamento = $medicamento;
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