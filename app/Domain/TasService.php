<?php

namespace App\Domain;

use App\Repositories\TasRepository;
use DateTime;
use Illuminate\Support\Facades\Hash;

class TasService
{
    private $tasRepository;

    public function __construct(TasRepository $tasRepository)
    {
        $this->tasRepository = $tasRepository;
    }

    public function encontrarUsuario($correo)
    {
        $this->tasRepository->beginTransaction();

        $usuario = $this->tasRepository->buscarUsuarioPorCorreo($correo);
        if (! $usuario) {
            return null;
        }

        return new Usuario(
            $usuario->id_usuario,
            $usuario->correo,
            $usuario->nip,
            $usuario->nombre,
            $usuario->apellido
        );
    }

    public function crearUsuario($correo, $nip, $nombre, $apellido)
    {
        $usuario = $this->encontrarUsuario($correo);
        if ($usuario) {
            $this->tasRepository->rollbackTransaction();

            return 'Advertencia: El correo ya se encuentra registrado';
        }

        $nipHash = Hash::make($nip);

        $usuarioNuevo = $this->tasRepository->crearUsuario($correo, $nipHash, $nombre, $apellido);
        if (! $usuarioNuevo) {
            $this->tasRepository->rollbackTransaction();

            return 'Advertencia: No se ha podido crear el usuario';
        }

        $this->tasRepository->commitTransaction();

        return new Usuario(
            $usuarioNuevo->id_usuario,
            $usuarioNuevo->correo,
            $usuarioNuevo->nip,
            $usuarioNuevo->nombre,
            $usuarioNuevo->apellido
        );
    }

    public function crearTarjeta(int $idUsuario, string $numero, string $fechaExp)
    {
        $this->tasRepository->beginTransaction();

        $numeroLimpio = preg_replace('/\D/', '', $numero);

        $last4 = substr($numeroLimpio, -4);

        $brand = $this->detectarBrand($numeroLimpio);

        $tarjetaModel = $this->tasRepository->crearTarjeta(
            $idUsuario,
            $last4,
            $brand,
            $fechaExp
        );

        if (! $tarjetaModel) {
            $this->tasRepository->rollbackTransaction();

            return 'Advertencia: No se pudo registrar la tarjeta.';
        }

        $this->tasRepository->commitTransaction();

        return new Tarjeta(
            $tarjetaModel->id_tarjeta,
            $tarjetaModel->id_usuario,
            $tarjetaModel->last4,
            $tarjetaModel->brand,
            $tarjetaModel->fecha_exp
        );
    }

    public function obtenerTarjetaUsuario($idUsuario)
    {
        $resultado = $this->tasRepository->obtenerTarjetaPorUsuario($idUsuario);

        if (! $resultado) {
            return null;
        }

        return new Tarjeta(
            $resultado->id_tarjeta,
            $resultado->id_usuario,
            $resultado->last4,
            $resultado->brand,
            $resultado->fecha_exp
        );
    }

    private function detectarBrand(string $num): string
    {
        if (preg_match('/^4/', $num)) {
            return 'visa';
        }
        if (preg_match('/^5[1-5]/', $num)) {
            return 'mastercard';
        }
        if (preg_match('/^3[47]/', $num)) {
            return 'amex';
        }

        return 'desconocido';
    }

    public function actualizarSesion(Usuario $usuario)
    {
        $this->tasRepository->actualizarStatusUsuario($usuario);
        $this->tasRepository->commitTransaction();
    }

    public function iniciarSesion(string $correo, string $nip)
    {
        $usuario = $this->encontrarUsuario($correo);
        if (! $usuario) {
            return 'Correo o contraseña incorrectos.';
        }

        $fechaActual = new DateTime;
        $usuario->setUltimoIntento($fechaActual);
        $bloqueadoHasta = $usuario->getBloqueadoHasta();

        if ($bloqueadoHasta && $bloqueadoHasta > $usuario->getUltimoIntento()) {
            $this->actualizarSesion($usuario);
            $fecha = $usuario->getBloqueadoHasta()->format('Y-m-d H:i:s');

            return 'Advertencia: Esta cuenta ha sido bloqueada hasta '.$fecha;
        }

        if (! Hash::check($nip, $usuario->getNip())) {

            $usuario->aumentarIntentosLogin();

            if ($usuario->getIntentosLogin() > 3) {
                $ultimoIntento = $usuario->getUltimoIntento();
                $nuevoBloqueo = (clone $ultimoIntento)->modify('+30 minutes');
                $usuario->setBloqueadoHasta($nuevoBloqueo);
                $usuario->reiniciarIntentosLogin();
                $this->actualizarSesion($usuario);
                $fecha = $usuario->getBloqueadoHasta()->format('Y-m-d H:i:s');

                return 'Advertencia: Esta cuenta ha sido bloqueada hasta '.$fecha;
            }

            $this->actualizarSesion($usuario);

            return 'Correo o contraseña incorrectos.';
        }

        if ($usuario->isSesionActiva()) {
            $this->tasRepository->rollbackTransaction();

            return 'Advertencia: Ya hay una sesión activa para esta cuenta';
        }

        $usuario->iniciarSesion();
        $usuario->reiniciarIntentosLogin();
        $this->actualizarSesion($usuario);

        return $usuario;
    }

    public function cerrarSesion($correo)
    {
        $usuario = $this->encontrarUsuario($correo);
        if (! $usuario) {
            return null;
        }

        $usuario->cerrarSesion();
        $this->actualizarSesion($usuario);
    }

    public function obtenerSucursales()
    {
        $modelos = $this->tasRepository->obtenerSucursales();

        if (! $modelos) {
            return [];
        }

        $sucursales = [];

        foreach ($modelos as $s) {

            $cadena = $s->cadena
                ? new Cadena(
                    $s->cadena->id_cadena,
                    $s->cadena->nombre
                )
                : null;

            $sucursal = new Sucursal(
                $s->id_sucursal,
                $cadena,
                $s->nombre,
                $s->latitud,
                $s->longitud
            );

            $sucursales[] = $sucursal->toArray();
        }

        return $sucursales;
    }
}
