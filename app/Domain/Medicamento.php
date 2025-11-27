<?php

namespace App\Domain;

class Medicamento
{
    private int $idMedicamento;
    private string $nombre;
    private string $especificacion;
    private string $laboratorio;

    public function __construct(
        int $idMedicamento,
        string $nombre,
        string $especificacion,
        string $laboratorio
    ) {
        $this->idMedicamento = $idMedicamento;
        $this->nombre = $nombre;
        $this->especificacion = $especificacion;
        $this->laboratorio = $laboratorio;
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
        'idMedicamento' => $this->idMedicamento,
        'nombre' => $this->nombre,
        'especificacion' => $this->especificacion,
        'laboratorio' => $this->laboratorio,
    ];
}


}
