<?php

namespace App\Repositories;

use App\Models\UsuarioModel;
use App\Services\Usuario;
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
}
