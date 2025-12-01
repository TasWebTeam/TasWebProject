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

    public function procesar(Sucursal $sucursal, SucursalService $sucursalService): void
    {
        $cantidadRequerida = $this->cantidad;
        $sucursalActual    = $sucursal;
        $buscarSucursales = true;
        $actualizarRepository = new ActualizarRepository();
        // $actualizarRepository->beginTransaction();
        $fueSurtido = false;
        // obtener todas las sucursales candidatas una sola vez
        while ($cantidadRequerida > 0) {

            $cantObtenida = $sucursalActual->verificarDisponibilidad(
                $cantidadRequerida,
                $this->medicamento,
                $actualizarRepository
            );

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
                $fueSurtido = true;
                break;
            }

            if($buscarSucursales) {
                $buscarSucursales = false;
                $candidatas = $sucursalService->obtenerSucursalesOrdenadasPorDistanciaYConStock(
                    $sucursal,
                    $this->medicamento,
                    $cantidadRequerida
                );
            }
            
            if (empty($candidatas)) {
                // ya no hay más sucursales, no se pudo surtir todo
                // INDICAR A MI TIO Y UN ROLLBACK
                $actualizarRepository->rollbackTransaction();
                dd("nambre");
                break;
            }
            // tomar la siguiente sucursal candidata (la más cercana disponible)
            $infoSucursal = array_shift($candidatas);
            $sucursalActual = $infoSucursal['sucursal'];
        }
        if($fueSurtido == false){
            dd("No se pudo surtir el medicamento " . $this->getMedicamento()->getNombre());
            // $actualizarRepository->rollbackTransaction();
        }
        // $actualizarRepository->commitTransaction();
        dd($this->getLineasSurtido());
    }

    public function abastecer(): void{
        
    }

    public function obtenerSucursalCercana(Sucursal $suc): void{
        
    }

    public function realizarDevolucion(): void{
        
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