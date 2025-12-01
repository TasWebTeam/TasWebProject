<?php

namespace App\Domain;

class Puesto
{
    private int $idPuesto;
    private string $nombre;
    private ?string $descripcion;

    public function __construct(
        int $idPuesto,
        string $nombre,
        ?string $descripcion = null
    ) {
        $this->idPuesto    = $idPuesto;
        $this->nombre      = $nombre;
        $this->descripcion = $descripcion;
    }

    public function getIdPuesto(): int
    {
        return $this->idPuesto;
    }

    public function setIdPuesto(int $idPuesto): void
    {
        $this->idPuesto = $idPuesto;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }
}
