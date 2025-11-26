<?php

namespace App\Services;

class Sucursal
{
    private int $id_sucursal;
    private string $id_cadena;
    private string $nombre;
    private float $latitud;
    private float $longitud;
    private Cadena $cadena;  

    public function __construct(
        int $id_sucursal,
        string $id_cadena,
        string $nombre,
        float $latitud,
        float $longitud,
        Cadena $cadena = null 
    ) {
        $this->id_sucursal = $id_sucursal;
        $this->id_cadena = $id_cadena;
        $this->nombre = $nombre;
        $this->latitud = $latitud;
        $this->longitud = $longitud;
        $this->cadena = $cadena;
    }

    public function getIdSucursal(): int { return $this->id_sucursal; }
    public function getIdCadena(): string { return $this->id_cadena; }
    public function getNombre(): string { return $this->nombre; }
    public function getLatitud(): float { return $this->latitud; }
    public function getLongitud(): float { return $this->longitud; }
    public function getCadena(): Cadena { return $this->cadena; }

    public function setIdSucursal(int $id_sucursal): void { $this->id_sucursal = $id_sucursal; }
    public function setIdCadena(string $id_cadena): void { $this->id_cadena = $id_cadena; }
    public function setNombre(string $nombre): void { $this->nombre = $nombre; }
    public function setLatitud(float $latitud): void { $this->latitud = $latitud; }
    public function setLongitud(float $longitud): void { $this->longitud = $longitud; }
    public function setCadena(?Cadena $cadena): void { $this->cadena = $cadena; }

    public function toArray(): array
    {
        return [
            'id_sucursal' => $this->id_sucursal,
            'id_cadena'   => $this->id_cadena,
            'nombre'      => $this->nombre,
            'latitud'     => $this->latitud,
            'longitud'    => $this->longitud,
            'cadena'      => $this->cadena->toArray(),
        ];
    }
}
