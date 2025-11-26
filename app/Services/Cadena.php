<?php

namespace App\Services;

class Cadena
{
    private string $id_cadena;
    private string $nombre;

    public function __construct(string $id_cadena, string $nombre)
    {
        $this->id_cadena = $id_cadena;
        $this->nombre = $nombre;
    }

    public function getIdCadena(): string
    {
        return $this->id_cadena;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setIdCadena(string $id_cadena): void
    {
        $this->id_cadena = $id_cadena;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function toArray(): array
    {
        return [
            'id_cadena' => $this->id_cadena,
            'nombre'    => $this->nombre,
        ];
    }
}
