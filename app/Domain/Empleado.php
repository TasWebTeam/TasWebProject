<?php

namespace App\Domain;

class Empleado extends Usuario{
    private int $puesto;
    private ?Sucursal $sucursal;

    public function __construct(
        int $idUsuario,
        string $nombre,
        string $apellido,
        string $correo,
        string $nip,
        int $puesto,
        ?Sucursal $sucursal = null
    ){
        parent::__construct($idUsuario, $nombre, $apellido, $correo, $nip);
        $this->puesto = $puesto;
        $this->sucursal = $sucursal;
        $this->setRol('Empleado');
    }

    public function getPuesto(): int
    {
        return $this->puesto;
    }

    public function setPuesto(int $puesto): void
    {
        $this->puesto = $puesto;
    }

    public function getSucursal(): ?Sucursal
    {
        return $this->sucursal;
    }

    public function setSucursal(?Sucursal $sucursal): void
    {
        $this->sucursal = $sucursal;
    }
    
    public function notificarReceta(): void{
        
    }
}