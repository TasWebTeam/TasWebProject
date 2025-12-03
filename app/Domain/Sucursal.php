<?php

namespace App\Domain;
use App\repositories\ConsultarRepository;
use App\Repositories\ActualizarRepository;
class Sucursal
{
    private int $id;
    private Cadena $cadena;
    private int $idSucursal;
    private string $nombre;
    private float $latitud;
    private float $longitud;

    public function __construct(
        int $id = 0,
        ?Cadena $cadena = null,
        int $id_sucursal = 0,
        string $nombre = "",
        float $latitud = 0.0,
        float $longitud = 0.0
    ) {
        $this->id = $id;
        $this->idSucursal = $id_sucursal;
        $this->cadena = $cadena;
        $this->nombre = $nombre;
        $this->latitud = $latitud;
        $this->longitud = $longitud;
    }
    
    public function confirmarRecetaNoRecogida(int $idReceta, string $estado): void{
        
    }

    public function getId(): int {
        return $this->id;
    }

    public function getIdSucursal(): int { 
        return $this->idSucursal; 
    }

    public function getCadena(): Cadena { 
        return $this->cadena; 
    }

    public function getNombre(): string { 
        return $this->nombre; 
    }

    public function getLatitud(): float {
         return $this->latitud; 
    }

    public function getLongitud(): float { 
        return $this->longitud; 
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setIdSucursal(int $idSucursal): void { 
        $this->idSucursal = $idSucursal; 
    }

    public function setCadena(?Cadena $cadena): void {
         $this->cadena = $cadena;
    }

    public function setNombre(string $nombre): void {
         $this->nombre = $nombre; 
    }

    public function setLatitud(float $latitud): void {
         $this->latitud = $latitud; 
    }

    public function setLongitud(float $longitud): void { 
        $this->longitud = $longitud; 
    }

    public function toArray(): array
    {
        return [
            'idSucursal' => $this->idSucursal,
            'cadena'      => $this->cadena->toArray(),
            'nombre'      => $this->nombre,
            'latitud'     => $this->latitud,
            'longitud'    => $this->longitud,
        ];
    }
}