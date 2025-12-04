<?php

namespace App\Domain;
use InvalidArgumentException;
class Paciente extends Usuario{

    private array $recetas = [];
    public function __construct(
        int $idUsuario,
        string $nombre,
        string $apellido,
        string $correo,
        string $nip,
        array $recetas = []
    ){
        parent::__construct($idUsuario, $nombre, $apellido, $correo, $nip);
        $this->setRol('Paciente');
        $this->setRecetas($recetas);
    }

      public function setRecetas(array $recetas): void{
        foreach ($recetas as $r) {
            if (!$r instanceof Receta) {
                throw new InvalidArgumentException("Todos los elementos deben ser Receta");
            }
        }
        $this->recetas = $recetas;
    }
    
    public function crearNuevaReceta(): void{
        $rec = Receta::nueva();
        $this->agregarReceta($rec);
    }

    public function getUltimaReceta(): ?Receta
    {
        if (empty($this->recetas)) {
            return null;
        }

        return $this->recetas[array_key_last($this->recetas)];
    }

    public function agregarReceta(Receta $receta): void{
        $this->recetas[] = $receta;
    }
}