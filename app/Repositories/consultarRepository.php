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
use DateTime;

class ConsultarRepository
{
    public function recuperarCadena($nombreCadena)
    {
        $cadenaModel = CadenaModel::where('nombre', $nombreCadena)->first();

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
        return $this->transformarSucursalModelADomain($sucursalModel);
    }
    
    private function transformarSucursalModelADomain(SucursalModel $sucursalModel): Sucursal
    {
        $cad = $this->transformarCadenaModelADomain($sucursalModel->cadena);

        return new Sucursal(
            $sucursalModel->id,
            $sucursalModel->id_sucursal,
            $cad,
            $sucursalModel->nombre,
            $sucursalModel->latitud,
            $sucursalModel->longitud
        );
    }

    // ESTE METODO NO SIRVE PARA CONSULTAR. ESTE ES PARA PROCESAR!!!!!!!!!!
    public function recuperarInventario(Cadena $Cadena, int $idSuc, string $nombreMedicamento): InventarioSucursal
    {
        $inventarioModel = InventarioModel::with(['medicamento', 'cadena', 'sucursal'])
            ->where('id_cadena', $Cadena->getIdCadena())
            ->where('id_sucursal', $idSuc)
            ->whereHas('medicamento', function ($q) use ($nombreMedicamento) {
                $q->where('nombre', $nombreMedicamento);
            })
            ->lockForUpdate()
            ->firstOrFail();
        dd($inventarioModel);
        return $this->transformarInventarioModelADomain($inventarioModel);
    }
    
    private function transformarInventarioModelADomain(InventarioModel $inventarioModel): InventarioSucursal
    {
        $sucursalDomain = $this->transformarSucursalModelADomain(
            $inventarioModel->sucursal
        );

        $medicamentoDomain = $this->transformarMedicamentoModelADomain(
            $inventarioModel->medicamento
        );

        return new InventarioSucursal(
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
            $medicamentoModel->nombre,
            $medicamentoModel->especificacion,
            $medicamentoModel->laboratorio
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
                $ls->cantidad,
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

        $table = (new SucursalModel())->getTable(); // 'sucursales'

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