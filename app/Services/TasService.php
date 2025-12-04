<?php

namespace App\Services;

use App\Repositories\TasRepository;
use DateTime;
use Illuminate\Support\Facades\Hash;
use App\Domain\Usuario;
use App\Domain\Tarjeta;
use App\Domain\Medicamento;
use App\Domain\Sucursal;
use App\Domain\Cadena;
use App\Domain\ImagenMedicamento;

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
        $usuarioModel = $this->tasRepository->buscarUsuarioPorCorreo($correo);
        if (! $usuarioModel) {
            return null;
        }
        $this->tasRepository->commitTransaction();

        $usuario = new Usuario(
            $usuarioModel->id_usuario,
            $usuarioModel->nombre,
            $usuarioModel->apellido,
            $usuarioModel->correo,
            $usuarioModel->nip
        );

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

    public function crearUsuario($correo, $nip, $nombre, $apellido)
    {
        $usuario = $this->encontrarUsuario($correo);
        $this->tasRepository->beginTransaction();
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

        $usuario = new Usuario(
            $usuarioNuevo->id_usuario,
            $usuarioNuevo->nombre,
            $usuarioNuevo->apellido,
            $usuarioNuevo->correo,
            $usuarioNuevo->nip
        );

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
        $this->tasRepository->commitTransaction();
        return $usuario;
    }

    private function limpiarNumeroTarjeta(string $numero): string
    {
        return preg_replace('/\D/', '', $numero);
    }

    private function obtenerLast4(string $numeroLimpio): string
    {
        return substr($numeroLimpio, -4);
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

    public function crearTarjeta(int $idUsuario, string $numero, string $fechaExp)
    {
        $this->tasRepository->beginTransaction();

        $numeroLimpio = $this->limpiarNumeroTarjeta($numero);
        $last4 = $this->obtenerLast4($numeroLimpio);
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

    public function actualizarTarjeta(int $idUsuario, string $numero, string $fechaExp)
    {
        $this->tasRepository->beginTransaction();

        try {
            $numeroLimpio = $this->limpiarNumeroTarjeta($numero);
            $last4 = $this->obtenerLast4($numeroLimpio);
            $brand = $this->detectarBrand($numeroLimpio);

            $tarjetaModel = $this->tasRepository->actualizarTarjeta(
                $idUsuario,
                $last4,
                $brand,
                $fechaExp
            );

            if (!$tarjetaModel) {
                $this->tasRepository->rollbackTransaction();
                return 'Advertencia: No se pudo actualizar la tarjeta.';
            }

            $this->tasRepository->commitTransaction();

            return new Tarjeta(
                $tarjetaModel->id_tarjeta,
                $tarjetaModel->id_usuario,
                $tarjetaModel->last4,
                $tarjetaModel->brand,
                $tarjetaModel->fecha_exp
            );
        } catch (\Exception $e) {
            $this->tasRepository->rollbackTransaction();
            return 'Error: ' . $e->getMessage();
        }
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

            return 'Advertencia: Esta cuenta ha sido bloqueada hasta ' . $fecha;
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

                return 'Advertencia: Esta cuenta ha sido bloqueada hasta ' . $fecha;
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

        $sessionData = [
            'id'       => $usuario->getId(),
            'correo'   => $usuario->getCorreo(),
            'nombre'   => $usuario->getNombre(),
            'apellido' => $usuario->getApellido(),
            'rol'      => $usuario->getRol(),
        ];

        if ($usuario->getRol() === 'empleado') {
            $empleado = $this->tasRepository->obtenerEmpleado($usuario);
            if ($empleado) {
                $sessionData['id_sucursal'] = $empleado->getSucursal()->getId(); 
                $sessionData['id_cadena'] = $empleado->getSucursal()->getCadena()->getIdCadena();
                $sessionData['nombre_sucursal'] = $empleado->getSucursal()->getNombre();
                $sessionData['nombre_cadena'] = $empleado->getSucursal()->getCadena()->getNombre();
            }
        }

        session(['usuario' => $sessionData]);

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

        if (!$modelos) {
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
                $s->id,
                $cadena,
                $s->id_sucursal,
                $s->nombre,
                $s->latitud,
                $s->longitud
            );

            $sucursales[] = $sucursal->toArray();
        }

        return $sucursales;
    }

// Agregar este método nuevo en TasService
public function obtenerIdSucursalPorNombre(string $nombreSucursal): ?int
{
    return $this->tasRepository->obtenerIdSucursalPorNombre($nombreSucursal);
}

// Actualizar el método buscarMedicamentos para crear objetos del dominio
public function buscarMedicamentos(string $query, int $idSucursal): array
{
    $resultados = $this->tasRepository->buscarMedicamentosPorNombre($query, $idSucursal);

    if (!$resultados || $resultados->isEmpty()) {
        return [];
    }

    $medicamentos = [];

    foreach ($resultados as $med) {
        // Crear objeto ImagenMedicamento si existe
        $imagenDomain = null;
        if (isset($med['imagen']) && $med['imagen']) {
            $imagenDomain = new ImagenMedicamento(
                $med['imagen']->idImagen,
                asset($med['imagen']->URL)
            );
        }

        // Crear objeto Medicamento del dominio
        $medicamento = new Medicamento(
            $med['id_medicamento'],
            $med['imagen']->idImagen ?? null,
            $med['nombre'],
            $med['especificacion'],
            $med['laboratorio'],
            $med['es_controlado'],
            $imagenDomain
        );

        // Convertir a array y agregar el precio (que no es parte del dominio Medicamento)
        $medicamentoArray = $medicamento->toArray();
        $medicamentoArray['precio'] = $med['precio']; // Agregar precio del inventario

        $medicamentos[] = $medicamentoArray;
    }

    return $medicamentos;
}

    public function obtenerMedicamentos(int $idMedicamento): ?Medicamento
    {
        $modelo = $this->tasRepository->obtenerMedicamentoPorId($idMedicamento);

        if (!$modelo) {
            return null;
        }

        $imagenDomain = $modelo->imagen
            ? new ImagenMedicamento(
                $modelo->imagen->idImagen,
                asset($modelo->imagen->URL)
            )
            : null;

        return new Medicamento(
            $modelo->id_medicamento,
            $modelo->idImagen,
            $modelo->nombre,
            $modelo->especificacion,
            $modelo->laboratorio,
            (bool) $modelo->es_controlado,
            $imagenDomain
        );
    }
}
