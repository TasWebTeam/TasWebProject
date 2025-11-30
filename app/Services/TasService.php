<?php

namespace App\Services;

use App\Repositories\TasRepository;
use App\Domain\Usuario;
use App\Domain\Sucursal;
use App\Domain\Cadena;
use App\Domain\Tarjeta;
use DateTime;
use Illuminate\Support\Facades\Hash;

class TasService
{
    private $tasRepository;

    public function __construct(TasRepository $tasRepository)
    {
        $this->tasRepository = $tasRepository;
    }

    /* ============================================================
     *  ENCONTRAR USUARIO POR CORREO (mapear dominio correctamente)
     * ============================================================ */
    public function encontrarUsuario($correo)
    {
        $this->tasRepository->beginTransaction();

        $usuarioModel = $this->tasRepository->buscarUsuarioPorCorreo($correo);

        if (!$usuarioModel) {
            $this->tasRepository->rollbackTransaction();
            return null;
        }

        // Crear dominio con los 5 datos del constructor
        $usuario = new Usuario(
            $usuarioModel->id_usuario,
            $usuarioModel->nombre,
            $usuarioModel->apellido,
            $usuarioModel->correo,
            $usuarioModel->nip
        );

        // Setters para completar resto de atributos
        $usuario->setSesionActiva((bool) $usuarioModel->sesion_activa);
        $usuario->setIntentosLogin((int) $usuarioModel->intentos_login);

        $usuario->setUltimoIntento(
            $usuarioModel->ultimo_intento
                ? new DateTime($usuarioModel->ultimo_intento)
                : null
        );

        $usuario->setBloqueadoHasta(
            $usuarioModel->bloqueado_hasta
                ? new DateTime($usuarioModel->bloqueado_hasta)
                : null
        );

        $usuario->setRol($usuarioModel->rol ?? '');

        return $usuario;
    }

    /* ============================================================
     *  CREAR NUEVO USUARIO
     * ============================================================ */
    public function crearUsuario($correo, $nip, $nombre, $apellido)
    {
        $usuarioExistente = $this->encontrarUsuario($correo);
        if ($usuarioExistente) {
            return 'Advertencia: El correo ya se encuentra registrado';
        }

        $nipHash = Hash::make($nip);

        $usuarioNuevo = $this->tasRepository->crearUsuario($correo, $nipHash, $nombre, $apellido);

        if (!$usuarioNuevo) {
            return 'Advertencia: No se ha podido crear el usuario';
        }

        // Crear dominio igual que encontrarUsuario
        $usuario = new Usuario(
            $usuarioNuevo->id_usuario,
            $usuarioNuevo->nombre,
            $usuarioNuevo->apellido,
            $usuarioNuevo->correo,
            $usuarioNuevo->nip
        );

        // Aplicar setters
        $usuario->setSesionActiva((bool) $usuarioNuevo->sesion_activa);
        $usuario->setIntentosLogin((int) $usuarioNuevo->intentos_login);

        $usuario->setUltimoIntento(
            $usuarioNuevo->ultimo_intento
                ? new DateTime($usuarioNuevo->ultimo_intento)
                : null
        );

        $usuario->setBloqueadoHasta(
            $usuarioNuevo->bloqueado_hasta
                ? new DateTime($usuarioNuevo->bloqueado_hasta)
                : null
        );

        $usuario->setRol($usuarioNuevo->rol ?? '');

        return $usuario;
    }

    /* ============================================================
     *  CREAR TARJETA
     * ============================================================ */
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

        if (!$tarjetaModel) {
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

        if (!$resultado) {
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

    /* ============================================================
     *  ACTUALIZAR ESTADO DEL USUARIO
     * ============================================================ */
    public function actualizarSesion(Usuario $usuario)
    {
        $this->tasRepository->actualizarStatusUsuario($usuario);
        $this->tasRepository->commitTransaction();
    }

    /* ============================================================
     *  INICIAR SESIÓN
     * ============================================================ */
    public function iniciarSesion(string $correo, string $nip)
    {
        $usuario = $this->encontrarUsuario($correo);

        if (!$usuario) {
            return 'Correo o contraseña incorrectos.';
        }

        // Validar bloqueos
        $fechaActual = new DateTime();
        $usuario->setUltimoIntento($fechaActual);

        if ($usuario->getBloqueadoHasta() && $usuario->getBloqueadoHasta() > $fechaActual) {
            $this->actualizarSesion($usuario);

            return 'Advertencia: Esta cuenta ha sido bloqueada hasta ' .
                $usuario->getBloqueadoHasta()->format('Y-m-d H:i:s');
        }

        // Verificar contraseña
        if (!Hash::check($nip, $usuario->getNip())) {

            $usuario->aumentarIntentosLogin();

            if ($usuario->getIntentosLogin() > 3) {
                $nuevoBloqueo = (clone $fechaActual)->modify('+30 minutes');
                $usuario->setBloqueadoHasta($nuevoBloqueo);
                $usuario->reiniciarIntentosLogin();

                $this->actualizarSesion($usuario);

                return 'Advertencia: Esta cuenta ha sido bloqueada hasta ' .
                    $usuario->getBloqueadoHasta()->format('Y-m-d H:i:s');
            }

            $this->actualizarSesion($usuario);
            return 'Correo o contraseña incorrectos.';
        }

        // Verificar sesión activa
        if ($usuario->isSesionActiva()) {
            return 'Advertencia: Ya hay una sesión activa para esta cuenta';
        }

        // Activar sesión
        $usuario->iniciarSesion();
        $usuario->reiniciarIntentosLogin();
        $this->actualizarSesion($usuario);

        // Guardar en sesión
        session([
            'usuario' => [
                'id'       => $usuario->getId(),
                'correo'   => $usuario->getCorreo(),
                'nombre'   => $usuario->getNombre(),
                'apellido' => $usuario->getApellido(),
                'rol'      => $usuario->getRol(),
            ]
        ]);

        return $usuario;
    }

    /* ============================================================
     *  CERRAR SESIÓN
     * ============================================================ */
    public function cerrarSesion($correo)
    {
        $usuario = $this->encontrarUsuario($correo);

        if (!$usuario) {
            return null;
        }

        $usuario->cerrarSesion();
        $this->actualizarSesion($usuario);
    }

    /* ============================================================
     *  OBTENER SUCURSALES (NO LO TOQUÉ)
     * ============================================================ */
    public function obtenerSucursales()
    {
        $modelos = $this->tasRepository->obtenerSucursales();

        if (!$modelos) {
            return [];
        }

        $sucursales = [];

        foreach ($modelos as $s) {
            $cadena = $s->cadena
                ? new Cadena($s->cadena->id_cadena, $s->cadena->nombre)
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
