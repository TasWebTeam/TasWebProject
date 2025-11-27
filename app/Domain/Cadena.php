<?php

namespace App\Domain;

class Cadena
{
    private string $idCadena;
    private string $nombre;

    public function __construct(string $idCadena, string $nombre)
    {
        $this->idCadena = $idCadena;
        $this->nombre = $nombre;
    }

    public function getIdCadena(): string
    {
        return $this->idCadena;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setIdCadena(string $idCadena): void
    {
        $this->idCadena = $idCadena;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function toArray(): array
    {
        return [
            'idCadena' => $this->idCadena,
            'nombre'    => $this->nombre,
        ];
    }
}