<?php
namespace App\Services;
use DateTime;
class Usuario
{
    private int $id;
    private string $correo;
    private string $nip;
    private string $nombre;
    private string $apellido;
    private bool $sesionActiva;
    private int $intentosLogin;
    private ?DateTime $ultimoIntento;
    private ?DateTime $bloqueadoHasta;

    public function __construct(
        int $id,
        string $correo,
        string $nip,
        string $nombre,
        string $apellido,
        bool $sesionActiva = false,
        int $intentosLogin = 0,
        ?DateTime $ultimoIntento = null,
        ?DateTime $bloqueadoHasta = null
    ) {
        $this->id = $id;
        $this->correo = $correo;
        $this->nip = $nip;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->sesionActiva = $sesionActiva;
        $this->intentosLogin = $intentosLogin;
        $this->ultimoIntento = $ultimoIntento;
        $this->bloqueadoHasta = $bloqueadoHasta;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCorreo(): string
    {
        return $this->correo;
    }

    public function getNip(): string
    {
        return $this->nip;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getApellido(): string
    {
        return $this->apellido;
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

    public function setCorreo(string $correo): void
    {
        $this->correo = $correo;
    }

    public function setNip(string $nip): void
    {
        $this->nip = $nip;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function setApellido(string $apellido): void
    {
        $this->apellido = $apellido;
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