<?php

namespace App\Repositories;

use App\Models\CadenaModel;
use App\Models\SucursalModel;
use App\Models\InventarioModel;
use App\Models\RecetaModel;
use App\Models\UsuarioModel;

use App\Domain\Cadena;
use App\Domain\Sucursal;
use App\Domain\InventarioSucursal;
use App\Domain\Receta;
use App\Domain\Paciente;
use App\Domain\Medicamento;
use App\Domain\DetalleReceta;
use App\Domain\LineaSurtido;
use App\Domain\Pago;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use DateTime;
use Exception;

class ConsultarRepository
{
    public function recuperarCadena($nombreCadena)
    {
        $cadenaModel = CadenaModel::where('nombre', $nombreCadena)->first();

        if (!$cadenaModel) {
            throw new Exception("No se encontró la cadena: {$nombreCadena}");
        }

        return $this->transformarCadenaModelADomain($cadenaModel);
    }
    
    private function transformarCadenaModelADomain(CadenaModel $cadenaModel): Cadena
    {
        return new Cadena(
            $cadenaModel->id_cadena,
            $cadenaModel->nombre
        );
    }
    
    public function recuperarSucursal($nombreSucursal, Cadena $cad): Sucursal
    {
        $sucursalModel = SucursalModel::with('cadena')
            ->where('nombre', $nombreSucursal)
            ->where('id_cadena', $cad->getIdCadena())
            ->first();

        if (!$sucursalModel) {
            throw new Exception("No se encontró la sucursal: {$nombreSucursal} para la cadena {$cad->getNombre()}");
        }

        return $this->transformarSucursalModelADomain($sucursalModel);
    }
    
    private function transformarSucursalModelADomain(SucursalModel $sucursalModel): Sucursal
    {
        $cad = $this->transformarCadenaModelADomain($sucursalModel->cadena);

        return new Sucursal(
            $sucursalModel->id,
            $cad,
            $sucursalModel->id_sucursal,
            $sucursalModel->nombre,
            $sucursalModel->latitud,
            $sucursalModel->longitud
        );
    }

    /**
     * Recuperar inventario para CONSULTA (sin lock)
     * Usado cuando solo se necesita leer información
     */
    public function recuperarInventarioConsultar(Cadena $Cadena, int $idSuc, string $nombreMedicamento)
    {
        try {
            Log::info('Recuperando inventario para consulta', [
                'cadena' => $Cadena->getIdCadena(),
                'sucursal' => $idSuc,
                'medicamento' => $nombreMedicamento
            ]);

            $inventarioModel = InventarioModel::with(['medicamento', 'cadena', 'sucursal'])
                ->where('id_cadena', $Cadena->getIdCadena())
                ->where('id_sucursal', $idSuc)
                ->whereHas('medicamento', function ($q) use ($nombreMedicamento) {
                    $q->where('nombre', $nombreMedicamento);
                })
                ->first();

            if (!$inventarioModel) {
                Log::warning('Inventario no encontrado', [
                    'cadena' => $Cadena->getIdCadena(),
                    'sucursal' => $idSuc,
                    'medicamento' => $nombreMedicamento
                ]);
                throw new Exception("Medicamento '{$nombreMedicamento}' no disponible en esta sucursal");
            }

            Log::info('Inventario encontrado', [
                'id_inventario' => $inventarioModel->id_inventario,
                'stock_actual' => $inventarioModel->stock_actual,
                'precio' => $inventarioModel->precio_actual
            ]);

            $inventario = $this->transformarInventarioModelADomain($inventarioModel);
            return $inventario;

        } catch (Exception $e) {
            Log::error('Error al recuperar inventario para consulta', [
                'error' => $e->getMessage(),
                'cadena' => $Cadena->getIdCadena(),
                'sucursal' => $idSuc,
                'medicamento' => $nombreMedicamento
            ]);
            throw $e;
        }
    }

    /**
     * Recuperar inventario con LOCK para ACTUALIZACIÓN
     * Usado cuando se va a modificar el stock
     */
    public function recuperarInventario(Cadena $Cadena, int $idSuc, string $nombreMedicamento)
    {
        try {
            
            $inventarioModel = InventarioModel::with(['medicamento', 'cadena', 'sucursal'])
                ->where('id_cadena', $Cadena->getIdCadena())
                ->where('id_sucursal', $idSuc)
                ->whereHas('medicamento', function ($q) use ($nombreMedicamento) {
                    $q->where('nombre', $nombreMedicamento);
                })
                ->lockForUpdate()
                ->first();

            $inventario = $this->transformarInventarioModelADomain($inventarioModel);
            return $inventario;

        } catch (Exception $e) {
            return null;
        }
    }
    
    private function transformarInventarioModelADomain(InventarioModel $inventarioModel): InventarioSucursal
    {
        if (!$inventarioModel->sucursal) {
            throw new Exception('La relación sucursal no está cargada en el inventario');
        }

        if (!$inventarioModel->medicamento) {
            throw new Exception('La relación medicamento no está cargada en el inventario');
        }

        $sucursalDomain = $this->transformarSucursalModelADomain(
            $inventarioModel->sucursal
        );

        $medicamentoDomain = $this->transformarMedicamentoModelADomain(
            $inventarioModel->medicamento
        );

        return new InventarioSucursal(
            $inventarioModel->id_inventario,
            $sucursalDomain,
            $medicamentoDomain,
            $inventarioModel->stock_minimo,
            $inventarioModel->stock_maximo,
            $inventarioModel->stock_actual,
            $inventarioModel->precio_actual
        );
    }

    private function transformarMedicamentoModelADomain($medicamentoModel): Medicamento
    {
        return new Medicamento(
            $medicamentoModel->id_medicamento,
            null,
            $medicamentoModel->nombre,
            $medicamentoModel->especificacion,
            $medicamentoModel->laboratorio,
            $medicamentoModel->es_controlado
        );
    }

    public function recuperarReceta(int $idReceta): Receta
    {
        $recetaModel = RecetaModel::with([
                'detalles.medicamento',
                'detalles.lineasSurtido.sucursalSurtido',
                'sucursalDestino',
                'pago',
            ])
            ->where('id_receta', $idReceta)
            ->firstOrFail();

        return $this->transformarRecetaModelADomain($recetaModel);
    }

    private function transformarPagoModelADomain($pagoModel): Pago
    {
        return new Pago(
            (float) $pagoModel->monto
        );
    }

    private function transformarDetallesModelADomain($detallesModel): array
    {
        $detalles = [];

        foreach ($detallesModel as $d) {
            $lineasDomain = $this->transformarLineasSurtidoModelADomain($d->lineasSurtido);

            $medicamentoDomain = $this->transformarMedicamentoModelADomain($d->medicamento);

            $detalles[] = new DetalleReceta(
                $medicamentoDomain,
                $d->cantidad,
                $d->precio,
                $lineasDomain
            );
        }

        return $detalles;
    }

    private function transformarLineasSurtidoModelADomain($lineasModel): array
    {
        $lineas = [];

        foreach ($lineasModel as $ls) {
            $sucursalDomain = $this->transformarSucursalModelADomain(
                $ls->sucursalSurtido
            );

            $lineas[] = new LineaSurtido(
                $sucursalDomain,
                $ls->estado_entrega,
                $ls->cantidad
            );
        }

        return $lineas;
    }

    private function transformarRecetaModelADomain(RecetaModel $recetaModel): Receta
    {
        $sucursalDomain = $this->transformarSucursalModelADomain(
            $recetaModel->sucursalDestino
        );

        $detallesDomain = $this->transformarDetallesModelADomain(
            $recetaModel->detalles
        );

        $pagoDomain = null;
        if ($recetaModel->pago) {
            $pagoDomain = $this->transformarPagoModelADomain($recetaModel->pago);
        }

        return new Receta(
            $recetaModel->id_receta,
            $sucursalDomain,
            $recetaModel->cedula_profesional,
            $recetaModel->fecha_registro
                ? new DateTime($recetaModel->fecha_registro)
                : null,
            $recetaModel->fecha_recoleccion
                ? new DateTime($recetaModel->fecha_recoleccion)
                : null,
            $recetaModel->estado_pedido,
            $detallesDomain,
            $pagoDomain
        );
    }

    public function recuperarPaciente(int $idUsuario): ?Paciente
    {
        $usuarioModel = UsuarioModel::where('id_usuario', $idUsuario)->first();

        if (!$usuarioModel) {
            return null;
        }

        return $this->transformarPacienteModelADomain($usuarioModel);
    }

    public function recuperarPacienteRecetas(int $idUsuario): ?Paciente
    {
        $usuarioModel = UsuarioModel::with([
                'recetas.detalles.lineasSurtido.sucursalSurtido',
                'recetas.sucursalDestino',
                'recetas.pago',
            ])
            ->where('id_usuario', $idUsuario)
            ->first();

        if (!$usuarioModel) {
            return null;
        }

        return $this->transformarPacienteModelADomain($usuarioModel);
    }

    private function transformarPacienteModelADomain(UsuarioModel $usuarioModel): Paciente
    {
        $recetasDomain = [];

        if ($usuarioModel->relationLoaded('recetas')) {
            foreach ($usuarioModel->recetas as $recetaModel) {
                $recetasDomain[] = $this->transformarRecetaModelADomain($recetaModel);
            }
        }

        return new Paciente(
            $usuarioModel->id_usuario,
            $usuarioModel->nombre,
            $usuarioModel->apellido,
            $usuarioModel->correo,
            $usuarioModel->nip,
            $recetasDomain
        );
    }

    public function buscarSucursalesCandidatas(
        Sucursal $sucursalOrigen,
        Medicamento $medicamento,
        int $limite = 20,
    ): array {
        $idMed = $medicamento->getIdMedicamento();

        $lat0 = $sucursalOrigen->getLatitud();
        $lon0 = $sucursalOrigen->getLongitud();

        $table = (new SucursalModel())->getTable(); 

        $haversine = "
            6371 * 2 * ASIN(
                SQRT(
                    POWER(SIN(RADIANS($table.latitud - ?) / 2), 2) +
                    COS(RADIANS(?)) * COS(RADIANS($table.latitud)) *
                    POWER(SIN(RADIANS($table.longitud - ?) / 2), 2)
                )
            )
        ";

        $query = SucursalModel::with('cadena')
            ->select("$table.*")
            ->selectRaw("$haversine AS distancia", [$lat0, $lat0, $lon0])
            ->whereHas('inventarios', function ($q) use ($idMed) {
                $q->where('inventarios.id_medicamento', $idMed)
                  ->where('inventarios.stock_actual', '>', 0);
            })
            ->orderBy('distancia', 'asc')
            ->limit($limite);

        $sucursalesModel = $query->get();

        $resultado = [];

        foreach ($sucursalesModel as $sucModel) {
            $mismaSucursal =
                $sucModel->id_sucursal === $sucursalOrigen->getIdSucursal()
                && $sucModel->id_cadena === $sucursalOrigen->getCadena()->getIdCadena();

            if ($mismaSucursal) {
                continue;
            }

            $resultado[] = $this->transformarSucursalModelADomain($sucModel);
        }

        return $resultado;
    }
}