<?php
namespace App\Domain;

class ImagenMedicamento
{
    private int $idImagen;
    private string $url;

    public function __construct(int $idImagen, string $url)
    {
        $this->idImagen = $idImagen;
        $this->url = $url;
    }

    public function getIdImagen(): int
    {
        return $this->idImagen;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function toArray(): array
    {
        return [
            'id_imagen' => $this->idImagen,
            'url' => $this->url
        ];
    }
}
