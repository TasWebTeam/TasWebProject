<?php

namespace App\Services;

use App\Repositories\TasRepository;
use Illuminate\Support\Facades\Hash;
use DateTime;

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
        if (!$usuario) return null;

        return new Usuario(
            $usuario->id_usuario,
            $usuario->correo,
            $usuario->nip,
            $usuario->nombre,
            $usuario->apellido,
            (bool) $usuario->sesion_activa,
            (int) $usuario->intentos_login,
            $usuario->ultimo_intento ? new DateTime($usuario->ultimo_intento) : null,
            $usuario->bloqueado_hasta ? new DateTime($usuario->bloqueado_hasta) : null,
            $usuario->rol
        );
    }

    public function crearUsuario($correo, $nip, $nombre, $apellido)
    {
        $existe = $this->encontrarUsuario($correo);
        if ($existe) {
            $this->tasRepository->rollbackTransaction();
            return "Advertencia: El correo ya se encuentra registrado";
        }

        $nipHash = Hash::make($nip);

        $usuario = $this->tasRepository->crearUsuario($correo, $nipHash, $nombre, $apellido);
        if (!$usuario) {
            $this->tasRepository->rollbackTransaction();
            return "Advertencia: No se ha podido crear el usuario";
        }

        $this->tasRepository->commitTransaction();
        return 1;
    }

    public function actualizarSesion(Usuario $usuario)
    {
        $this->tasRepository->actualizarStatusUsuario($usuario);
        $this->tasRepository->commitTransaction();
    }

    public function iniciarSesion(string $correo, string $nip)
    {
        $usuario = $this->encontrarUsuario($correo);
        if (!$usuario) return "Correo o contraseña incorrectos.";

        $fechaActual = new DateTime();
        $usuario->setUltimoIntento($fechaActual);
        $bloqueadoHasta = $usuario->getBloqueadoHasta();

        if ($bloqueadoHasta && $bloqueadoHasta > $usuario->getUltimoIntento()) {
            $this->actualizarSesion($usuario);
            $fecha = $usuario->getBloqueadoHasta()->format('Y-m-d H:i:s');
            return "Advertencia: Esta cuenta ha sido bloqueada hasta " . $fecha;
        }

        if (!Hash::check($nip, $usuario->getNip())) {

            $usuario->aumentarIntentosLogin();

            if ($usuario->getIntentosLogin() > 3) {
                $ultimoIntento = $usuario->getUltimoIntento();
                $nuevoBloqueo = (clone $ultimoIntento)->modify('+30 minutes');
                $usuario->setBloqueadoHasta($nuevoBloqueo);
                $usuario->reiniciarIntentosLogin();
                $this->actualizarSesion($usuario);
                $fecha = $usuario->getBloqueadoHasta()->format('Y-m-d H:i:s');
                return "Advertencia: Esta cuenta ha sido bloqueada hasta " . $fecha;
            }

            $this->actualizarSesion($usuario);
            return "Correo o contraseña incorrectos.";
        }

        if ($usuario->isSesionActiva()) {
            $this->tasRepository->rollbackTransaction();
            return "Advertencia: Ya hay una sesión activa para esta cuenta";
        }

        $usuario->iniciarSesion();
        $usuario->reiniciarIntentosLogin();
        $this->actualizarSesion($usuario);

        return $usuario;
    }

    public function cerrarSesion($correo)
    {
        $usuario = $this->encontrarUsuario($correo);
        if (!$usuario) return null;

        $usuario->cerrarSesion();
        $this->actualizarSesion($usuario);
    }
}