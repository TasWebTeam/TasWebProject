<?php 

namespace App\Domain;

class LineaSurtido
{
    private Sucursal $sucursal;
    private String $estadoEntrega;
    private int $cantidad;

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

    public function devolverASucursal(int $cantidad): void
    {
       
    }


}