<?php
namespace App\Domain;

use DateTime;

class Usuario
{
    private int $idUsuario;
    private string $nombre;
    private string $apellido;
    private string $correo;
    private string $nip;
    private bool $sesionActiva;
    private int $intentosLogin;
    private ?DateTime $ultimoIntento;
    private ?DateTime $bloqueadoHasta;
    private string $rol;

    public function __construct(
        int $idUsuario,
        string $nombre,
        string $apellido,
        string $correo,
        string $nip
    ) {
        $this->idUsuario = $idUsuario;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->correo = $correo;
        $this->nip = $nip;
        $this->sesionActiva = false;
        $this->intentosLogin = 0;
        $this->ultimoIntento = null;
        $this->bloqueadoHasta = null;
        $this->rol = '';
    }

    public function getId(): int
    {
        return $this->idUsuario;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getApellido(): string
    {
        return $this->apellido;
    }

    public function getCorreo(): string
    {
        return $this->correo;
    }

    public function getNip(): string
    {
        return $this->nip;
    }

    public function isSesionActiva(): bool
    {
        return $this->sesionActiva;
    }

    public function getIntentosLogin(): int
    {
        return $this->intentosLogin;
    }

    public function getUltimoIntento(): ?DateTime
    {
        return $this->ultimoIntento;
    }

    public function getBloqueadoHasta(): ?DateTime
    {
        return $this->bloqueadoHasta;
    }

    public function getRol(): string { return $this->rol; }     

    public function setCorreo(string $correo): void
    {
        $this->correo = $correo;
    }


    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function setApellido(string $apellido): void
    {
        $this->apellido = $apellido;
    }

    public function setNip(string $nip): void
    {
        $this->nip = $nip;
    }

    public function setSesionActiva(bool $sesionActiva): void
    {
        $this->sesionActiva = $sesionActiva;
    }

    public function setIntentosLogin(int $intentosLogin): void
    {
        $this->intentosLogin = $intentosLogin;
    }

    public function setUltimoIntento(?DateTime $ultimoIntento): void
    {
        $this->ultimoIntento = $ultimoIntento;
    }

    public function setBloqueadoHasta(?DateTime $bloqueadoHasta): void
    {
        $this->bloqueadoHasta = $bloqueadoHasta;
    }

    public function setRol(string $rol): void 
    { 
        $this->rol = $rol;
    }

    public function aumentarIntentosLogin(): void
    {
        $this->intentosLogin++;
    }

    public function reiniciarIntentosLogin(): void
    {
        $this->intentosLogin = 0;
    }

    public function iniciarSesion(): void
    {
        $this->setSesionActiva(true);
    }

    public function cerrarSesion(): void
    {
        $this->setSesionActiva(false);
    }
}