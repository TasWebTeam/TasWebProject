<?php

namespace App\Services;
use DateTime;

class Receta
{
    // --------- Atributos ---------
    private int $idReceta;
    private string $cedulaProfesional;
    private DateTime $fechaRegistro;
    private DateTime $fechaRecoleccion;
    private string $estadoPedido;
    private array $detallesReceta = [];



    // --------- Constructor ---------
    public function __construct(
        int $idReceta,
        string $cedulaProfesional,
        string $fechaRegistro,
        string $fechaRecoleccion,
        string $estadoPedido
    ) {
        $this->idReceta = $idReceta;
        $this->cedulaProfesional = $cedulaProfesional;
        $this->fechaRegistro = $fechaRegistro;
        $this->fechaRecoleccion = $fechaRecoleccion;
        $this->estadoPedido = $estadoPedido;
    }

    public function obtenerSucursal(string $nombreSucursal, string $nombreCadena)
    {
        // lógica de dominio pendiente
    }

    public function asignarCadena($cad)
    {
        // lógica de dominio pendiente
    }

    public function asignarSucursal($suc)
    {
        // lógica de dominio pendiente
    }

    public function introducirCedulaProfesional(string $cedulaProfesional)
    {
        // lógica de dominio pendiente
    }

    public function crearDetalleReceta(string $nombreMedicamento, int $cantidad)
    {
        // lógica de dominio pendiente
    }

    public function calcularComision()
    {
        // lógica de dominio pendiente
    }

    public function procesarReceta()
    {
        // lógica de dominio pendiente
    }

    public function calcularFecha()
    {
        // lógica de dominio pendiente
    }

    public function devolverMedicamentos()
    {
        // lógica de dominio pendiente
    }

    public function cambiarEstado(string $estado)
    {
        // lógica de dominio pendiente
    }

    // --------- GETTERS ---------

    public function getIdReceta(): int
    {
        return $this->idReceta;
    }

    public function getCedulaProfesional(): string
    {
        return $this->cedulaProfesional;
    }

    public function getFechaRegistro(): string
    {
        return $this->fechaRegistro;
    }

    public function getFechaRecoleccion(): string
    {
        return $this->fechaRecoleccion;
    }

    public function getEstadoPedido(): string
    {
        return $this->estadoPedido;
    }

    // --------- SETTERS ---------

    public function setIdReceta(int $idReceta): void
    {
        $this->idReceta = $idReceta;
    }

    public function setCedulaProfesional(string $cedulaProfesional): void
    {
        $this->cedulaProfesional = $cedulaProfesional;
    }

    public function setFechaRegistro(string $fechaRegistro): void
    {
        $this->fechaRegistro = $fechaRegistro;
    }

    public function setFechaRecoleccion(string $fechaRecoleccion): void
    {
        $this->fechaRecoleccion = $fechaRecoleccion;
    }

    public function setEstadoPedido(string $estadoPedido): void
    {
        $this->estadoPedido = $estadoPedido;
    }
}
