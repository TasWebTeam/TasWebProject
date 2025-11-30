<?php

namespace App\Repositories;

use App\Models\SucursalModel;
use App\Models\CadenaModel;
use App\Models\TarjetaModel;
use App\Models\UsuarioModel;
use App\Models\EmpleadoModel;
use App\Domain\Usuario;
use App\Domain\Empleado;
use App\Domain\Sucursal;
use App\Domain\Cadena;
use Illuminate\Support\Facades\DB;

class TasRepository
{
    public function beginTransaction()
    {
        DB::beginTransaction();
    }

    public function commitTransaction()
    {
        DB::commit();
    }

    public function rollbackTransaction()
    {
        DB::rollBack();
    }

    public function crearUsuario($correo, $nipHash, $nombre, $apellido)
    {
        try {
            return UsuarioModel::create([
                'correo' => $correo,
                'nip' => $nipHash,
                'nombre' => $nombre,
                'apellido' => $apellido,
                'sesion_activa' => 0,
                'intentos_login' => 0,
                'ultimo_intento' => null,
                'bloqueado_hasta' => null,
                'rol' => 'paciente',
            ]);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function crearTarjeta($idUsuario, $last4, $brand, $fechaExp)
    {
        try {
            return TarjetaModel::create([
                'id_usuario' => $idUsuario,
                'last4' => $last4,
                'brand' => $brand,
                'fecha_exp' => $fechaExp,
            ]);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function obtenerTarjetaPorUsuario($idUsuario){
         try {
            return TarjetaModel::where('id_usuario', $idUsuario)
                ->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function actualizarTarjeta($idUsuario, $last4, $brand, $fechaExp)
{
    try {
        $tarjeta = TarjetaModel::where('id_usuario', $idUsuario)->first();
        
        if (!$tarjeta) {
            return null;
        }

        $tarjeta->last4 = $last4;
        $tarjeta->brand = $brand;
        $tarjeta->fecha_exp = $fechaExp;
        $tarjeta->save();

        return $tarjeta;
    } catch (\Exception $e) {
        return null;
    }
}

    public function buscarUsuarioPorCorreo(string $correo)
    {
        try {
            return UsuarioModel::where('correo', $correo)
                ->lockForUpdate()
                ->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function actualizarStatusUsuario(Usuario $usuario)
    {
        try {
            $usuarioModel = UsuarioModel::find($usuario->getId());
            $usuarioModel->sesion_activa = $usuario->isSesionActiva();
            $usuarioModel->intentos_login = $usuario->getIntentosLogin();
            $usuarioModel->ultimo_intento = $usuario->getUltimoIntento()?->format('Y-m-d H:i:s');
            $usuarioModel->bloqueado_hasta = $usuario->getBloqueadoHasta()?->format('Y-m-d H:i:s');

            $usuarioModel->save();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function obtenerSucursales()
    {
        try {
            return SucursalModel::with('cadena:id_cadena,nombre')
                ->select('id_cadena', 'id_sucursal', 'nombre', 'latitud', 'longitud')
                ->get();
        } catch (\Exception $e) {
            return null;
        }
    }

    /*public function obtenerEmpleado($usuario){
        try{
            return EmpleadoModel::where('id_usuario', $usuario->getId())->first();
        } catch(\Exception $e){
            return null;
        }
    }*/

    public function obtenerEmpleado(Usuario $usuario): ?Empleado
{
    try {
        // Traemos también el puesto (relación en EmpleadoModel)
        $empleadoModel = EmpleadoModel::with('puesto')
            ->where('id_usuario', $usuario->getId())
            ->first();

        if (!$empleadoModel) {
            return null;
        }

        // Construimos el dominio Empleado a partir del Usuario + puesto
        $empleado = new Empleado(
            $usuario->getId(),
            $usuario->getNombre(),
            $usuario->getApellido(),
            $usuario->getCorreo(),
            $usuario->getNip(),
            $empleadoModel->puesto ? $empleadoModel->puesto->nombre : '',
            $this->obtenerSucursal($empleadoModel->id_sucursal)
        );

        // Copiamos el estado de sesión & bloqueo desde el Usuario
        $empleado->setSesionActiva($usuario->isSesionActiva());
        $empleado->setIntentosLogin($usuario->getIntentosLogin());
        $empleado->setUltimoIntento($usuario->getUltimoIntento());
        $empleado->setBloqueadoHasta($usuario->getBloqueadoHasta());
        $empleado->setRol($usuario->getRol());

        return $empleado;

        }catch (\Exception $e) {
            return null;
        }
    }

    private function obtenerSucursal(int $idSucursal) {
        $sucursalEloquent = SucursalModel::with('cadena')->find($idSucursal);

        if (!$sucursalEloquent) {
            return null;
        }

        $sucursalDomain = new Sucursal(
            $sucursalEloquent->id_sucursal,
            $this->obtenerCadena($sucursalEloquent->id_cadena),
            $sucursalEloquent->nombre,
            $sucursalEloquent->latitud,
            $sucursalEloquent->longitud,
        );

        return $sucursalDomain;
    }

    private function obtenerCadena(int $idCadena){
        $cadenaEloquent = CadenaModel::with('cadena')->find($idCadena);

        if (!$cadenaEloquent) {
            return null;
        }
        $cadenaDomain = new Cadena(
            $cadenaEloquent->id_cadena,
            $cadenaEloquent->nombre
        );

        return $cadenaDomain;
    }











}
