<?php

namespace App\Domain;

class Empleado extends Usuario{
    private Puesto $puesto;
    private ?Sucursal $sucursal;

    public function __construct(
        int $idUsuario,
        string $nombre,
        string $apellido,
        string $correo,
        string $nip,
        Puesto $puesto,
        ?Sucursal $sucursal = null
    ){
        parent::__construct($idUsuario, $nombre, $apellido, $correo, $nip);
        $this->puesto = $puesto;
        $this->sucursal = $sucursal;
        $this->setRol('Empleado');
    }

    public function getPuesto(): Puesto
    {
        return $this->puesto;
    }

    public function setPuesto(Puesto $puesto): void
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