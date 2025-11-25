<?php

namespace App\Services;

class Tarjeta
{
    private int $id_tarjeta;
    private int $id_usuario;
    private string $last4;
    private string $brand;
    private string $fecha_exp;

    public function __construct(
        int $id_tarjeta,
        int $id_usuario,
        string $last4,
        string $brand,
        string $fecha_exp,
    ) {
        $this->id_tarjeta = $id_tarjeta;
        $this->id_usuario = $id_usuario;
        $this->last4 = $last4;
        $this->brand = $brand;
        $this->fecha_exp = $fecha_exp;
    }

    public function getIdTarjeta(): int
    {
        return $this->id_tarjeta;
    }

    public function getIdUsuario(): int
    {
        return $this->id_usuario;
    }

    public function getLast4(): string
    {
        return $this->last4;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getFechaExp(): string
    {
        return $this->fecha_exp;
    }

    public function setIdTarjeta(int $id_tarjeta): void
    {
        $this->id_tarjeta = $id_tarjeta;
    }

    public function setIdUsuario(int $id_usuario): void
    {
        $this->id_usuario = $id_usuario;
    }

    public function setLast4(string $last4): void
    {
        $this->last4 = $last4;
    }

    public function setBrand(string $brand): void
    {
        $this->brand = $brand;
    }

    public function setFechaExp(string $fecha_exp): void
    {
        $this->fecha_exp = $fecha_exp;
    }
}
