<?php

namespace App\Domain;

class Empleado extends Usuario{
    private string $puesto;

    public function __construct(
        int $idUsuario,
        string $nombre,
        string $apellido,
        string $correo,
        string $nip,
        string $puesto
    ){
        parent::__construct($idUsuario, $nombre, $apellido, $correo, $nip);
        $this->puesto = $puesto;
        $this->setRol('Empleado');
    }

    public function getPuesto(): string
    {
        return $this->puesto;
    }

    public function setPuesto(string $puesto): void
    {
        $this->puesto = $puesto;
    }

    public function notificarReceta(): void{
        
    }
}