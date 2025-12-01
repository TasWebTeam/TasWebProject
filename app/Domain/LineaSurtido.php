<?php 

namespace App\Domain;
use App\Repositories\ConsultarRepository;
use App\Repositories\ActualizarRepository;

class LineaSurtido
{
    private Sucursal $sucursal;
    private String $estadoEntrega;
    private int $cantidad;
    private ConsultarRepository $consultarRepo;

    public function __construct(Sucursal $sucursal, String $estadoEntrega, int $cantidad)
    {
        $this->sucursal = $sucursal;
        $this->estadoEntrega = $estadoEntrega;
        $this->cantidad = $cantidad;
    }

    public function getSucursal(): Sucursal
    {
        return $this->sucursal;
    }

    public function getEstadoEntrega(): String
    {
        return $this->estadoEntrega;
    }

    public function getCantidad(): int
    {
        return $this->cantidad;
    }

    public function setSucursal(Sucursal $sucursal): void
    {
        $this->sucursal = $sucursal;
    }

    public function setEstadoEntrega(String $estadoEntrega): void
    {
        $this->estadoEntrega = $estadoEntrega;
    }

    public function setCantidad(int $cantidad): void
    {
        $this->cantidad = $cantidad;
    }

    public function toArray(): array
    {
        return [
            'sucursal' => $this->sucursal->toArray(),
            'estadoEntrega' => $this->estadoEntrega,
            'cantidad' => $this->cantidad,
        ];
    }

    /*public function devolverASucursal(int $cantidad,string $nombreMedicamento): void
    {
       $consultarRepository = new ConsultarRepository();
       $inv = $consultarRepository->recuperarInventario(
      $this->getSucursal()->getCadena(),
       $this->getSucursal()->getIdSucursal(),
       $nombreMedicamento);

       
       $inv->devolverMedicamento($cantidad);
    }*/
    
    public function devolverASucursal(int $cantidad, string $nombreMedicamento): void
    {
        $consultarRepository = new ConsultarRepository();
        $actualizarRepository = new ActualizarRepository();

        // 🔹 Empezamos transacción a nivel BD
        $actualizarRepository->beginTransaction();

        try {
            // 1) Recuperar inventario de esta sucursal + medicamento
            $inv = $consultarRepository->recuperarInventario(
                $this->getSucursal()->getCadena(),         // Cadena (dominio)
                $this->getSucursal()->getIdSucursal(),     // id sucursal
                $nombreMedicamento                         // o un id, según tu implementación
            );

            // 2) Actualizar dominio (stockActual += cantidad)
            $inv->devolverMedicamento($cantidad);

            // 3) Persistir inventario en tabla `inventarios`
            $actualizarRepository->actualizarInventario(
                $this->getSucursal()->getCadena(),
                $this->getSucursal()->getIdSucursal(),
                $inv
            );

            // 4) Confirmar transacción
            $actualizarRepository->commitTransaction();

        } catch (\Exception $e) {
            // Si algo falla, revertir cambios
            $actualizarRepository->rollbackTransaction();
            // opcional: lanzar de nuevo la excepción o loguearla
            // throw $e;
        }
    }
}