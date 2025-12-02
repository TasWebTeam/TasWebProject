<?php

namespace App\Domain;

use App\Domain\ImagenMedicamento;

class Medicamento
{
    private int $idMedicamento;
    private ?int $idImagen;
    private string $nombre;
    private string $especificacion;
    private string $laboratorio;
    private bool $esControlado;
    private ?ImagenMedicamento $imagen;

    public function __construct(
        int $idMedicamento,
        ?int $idImagen,
        string $nombre,
        string $especificacion,
        string $laboratorio,
        bool $esControlado,
        ?ImagenMedicamento $imagen = null
    ) {
        $this->idMedicamento = $idMedicamento;
        $this->idImagen = $idImagen;
        $this->nombre = $nombre;
        $this->especificacion = $especificacion;
        $this->laboratorio = $laboratorio;
        $this->esControlado = $esControlado;
        $this->imagen = $imagen;
    }

    public function getIdMedicamento(): int
    {
        return $this->idMedicamento;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getEspecificacion(): string
    {
        return $this->especificacion;
    }

    public function getLaboratorio(): string
    {
        return $this->laboratorio;
    }

    public function setIdMedicamento(int $idMedicamento): void
    {
        $this->idMedicamento = $idMedicamento;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function setEspecificacion(string $especificacion): void
    {
        $this->especificacion = $especificacion;
    }

    public function setLaboratorio(string $laboratorio): void
    {
        $this->laboratorio = $laboratorio;
    }

    public function toArray(): array
    {
        return [
            'id_medicamento' => $this->idMedicamento,
            'nombre' => $this->nombre,
            'especificacion' => $this->especificacion,
            'laboratorio' => $this->laboratorio,
            'es_controlado' => $this->esControlado,
            'imagen' => $this->imagen ? $this->imagen->toArray() : null
        ];
    }
}
