<?php

namespace App\Domain;

class Tarjeta
{
    private int $idTarjeta;
    private int $idUsuario;
    private string $last4;
    private string $brand;
    private string $fechaExp;

    public function __construct(
        int $idTarjeta,
        int $idUsuario,
        string $last4,
        string $brand,
        string $fechaExp,
    ) {
        $this->idTarjeta = $idTarjeta;
        $this->idUsuario = $idUsuario;
        $this->last4 = $last4;
        $this->brand = $brand;
        $this->fechaExp = $fechaExp;
    }

    public function getIdTarjeta(): int
    {
        return $this->idTarjeta;
    }

    public function getIdUsuario(): int
    {
        return $this->idUsuario;
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
        return $this->fechaExp;
    }

    public function setIdTarjeta(int $idTarjeta): void
    {
        $this->idTarjeta = $idTarjeta;
    }

    public function setIdUsuario(int $idUsuario): void
    {
        $this->idUsuario = $idUsuario;
    }

    public function setLast4(string $last4): void
    {
        $this->last4 = $last4;
    }

    public function setBrand(string $brand): void
    {
        $this->brand = $brand;
    }

    public function setFechaExp(string $fechaExp): void
    {
        $this->fechaExp = $fechaExp;
    }
}