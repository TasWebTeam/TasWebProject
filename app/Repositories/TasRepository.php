<?php

namespace App\Repositories;

use App\Models\SucursalModel;
use App\Models\CadenaModel;
use App\Models\TarjetaModel;
use App\Models\UsuarioModel;
use App\Models\EmpleadoModel;
use App\Models\PuestoModel;
use App\Domain\Usuario;
use App\Domain\Empleado;
use App\Domain\Sucursal;
use App\Domain\Cadena;
use App\Domain\Medicamento;
use App\Domain\Puesto;
use App\Models\MedicamentoModel;
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
                ->select('id','id_cadena', 'id_sucursal', 'nombre', 'latitud', 'longitud')
                ->get();
        } catch (\Exception $e) {
            return null;
        }
    }
public function obtenerIdSucursalPorNombre(string $nombreSucursal): ?int
{
    try {
        $sucursal = SucursalModel::where('nombre', $nombreSucursal)->first();
        return $sucursal ? $sucursal->id : null;
    } catch (\Exception $e) {
        \Log::error('Error al obtener ID de sucursal', [
            'nombre' => $nombreSucursal,
            'error' => $e->getMessage()
        ]);
        return null;
    }
}
    public function buscarMedicamentosPorNombre(string $query, int $idSucursal, int $limit = 10)
{
    try {
        return MedicamentoModel::with(['imagen', 'inventarios' => function($q) use ($idSucursal) {
                $q->where('id_sucursal', $idSucursal);
            }])
            ->where('nombre', 'LIKE', "{$query}%")
            ->limit($limit)
            ->get()
            ->map(function($medicamento) {
                // Obtener el precio del inventario de la sucursal específica
                $inventario = $medicamento->inventarios->first();
                
                return [
                    'id_medicamento' => $medicamento->id_medicamento,
                    'nombre' => $medicamento->nombre,
                    'especificacion' => $medicamento->especificacion,
                    'laboratorio' => $medicamento->laboratorio,
                    'es_controlado' => $medicamento->es_controlado,
                    'imagen' => $medicamento->imagen,
                    'precio' => $inventario ? $inventario->precio_actual : 0,
                ];
            });
    } catch (\Exception $e) {
        Log::error('Error al buscar medicamentos', [
            'error' => $e->getMessage(),
            'query' => $query,
            'id_sucursal' => $idSucursal
        ]);
        return collect([]);
    }
}
    public function obtenerMedicamentoPorId(int $idMedicamento)  
    {
        try {
            return MedicamentoModel::with('imagen')->find($idMedicamento);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function obtenerEmpleado(Usuario $usuario): ?Empleado
    {
        try {
            $empleadoModel = EmpleadoModel::with('puesto')
                ->where('id_usuario', $usuario->getId())
                ->first();
            if (!$empleadoModel) {
                return null;
            }

            $empleado = new Empleado(
                $usuario->getId(),
                $usuario->getNombre(),
                $usuario->getApellido(),
                $usuario->getCorreo(),
                $usuario->getNip(),
                $this->obtenerPuesto($empleadoModel->id_puesto),
                $this->obtenerSucursal($empleadoModel->id_sucursal)
            );

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
            $sucursalEloquent->id,
            $this->obtenerCadena($sucursalEloquent->id_cadena),
            $sucursalEloquent->id_sucursal,
            $sucursalEloquent->nombre,
            $sucursalEloquent->latitud,
            $sucursalEloquent->longitud
        );

        return $sucursalDomain;
    }

    private function obtenerCadena(string $idCadena){
        $cadenaEloquent = CadenaModel::find($idCadena);

        if (!$cadenaEloquent) {
            return null;
        }
        $cadenaDomain = new Cadena(
            $cadenaEloquent->id_cadena,
            $cadenaEloquent->nombre
        );

        return $cadenaDomain;
    }

    private function obtenerPuesto(string $idPuesto){
        $puestoEloquent = PuestoModel::find($idPuesto);

        if (!$puestoEloquent) {
            return null;
        }
        $puestoDomain = new Puesto(
            $puestoEloquent->id_puesto,
            $puestoEloquent->nombre,
            $puestoEloquent->descripcion
        );

        return $puestoDomain;
    }

}