<?php

namespace App\Services;

class Medicamento
{
    private int $idMedicamento;
    private int $idImagen;
    private string $nombre;
    private string $especificacion;
    private string $laboratorio;
    private bool $esControlado;
    private string $url;

    public function __construct(
        int $idMedicamento,
        int $idImagen,
        string $nombre,
        string $especificacion,
        string $laboratorio,
        bool $esControlado,
        string $url
    ) {
        $this->idMedicamento = $idMedicamento;
        $this->idImagen = $idImagen;
        $this->nombre = $nombre;
        $this->especificacion = $especificacion;
        $this->laboratorio = $laboratorio;
        $this->esControlado = $esControlado;
        $this->url = $url;
    }
    
    public function getIdMedicamento(): int
    {
        return $this->idMedicamento;
    }

    public function getIdImagen(): int
    {
        return $this->idImagen;
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

    public function isEsControlado(): bool
    {
        return $this->esControlado;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setIdMedicamento(int $idMedicamento): void
    {
        $this->idMedicamento = $idMedicamento;
    }

    public function setIdImagen(int $idImagen): void
    {
        $this->idImagen = $idImagen;
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

    public function setEsControlado(bool $esControlado): void
    {
        $this->esControlado = $esControlado;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function toArray(): array
    {
        return [
            'id_medicamento' => $this->idMedicamento,
            'nombre' => $this->nombre,
            'especificacion' => $this->especificacion,
            'laboratorio' => $this->laboratorio ?? 'Sin especificar',
            'es_controlado' => $this->esControlado,
            'url' => $this->url ?? asset('images/medicamentos/default.png')
        ];
    }
}
