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
        //recuperar el modelo de cadena desde la base de datos
        $cadenaModel = CadenaModel::where('nombre', $nombreCadena)->first();
        //transformar el modelo de cadena a dominio de cadena
        return $this->transformarCadenaModelADomain($cadenaModel);
    }
    
    // transformar cadenamodel a cadenadomain uno por uno
    private function transformarCadenaModelADomain(CadenaModel $cadenaModel)
    {
        return new Cadena(
            $cadenaModel->id_cadena,
            $cadenaModel->nombre
        );
    }
    
    public function recuperarSucursal($nombreSucursal, Cadena $cad){
        $sucursalModel = SucursalModel::with('cadena')
            ->where('nombre', $nombreSucursal)
            ->where('id_cadena', $cad->getIdCadena())
            ->first();
        return $this->transformarSucursalModelADomain($sucursalModel);
    }
    
    private function transformarSucursalModelADomain(SucursalModel $sucursalModel)
    {
        $cad = $this->transformarCadenaModelADomain($sucursalModel->cadena);
        return new Sucursal(
            $sucursalModel->id_sucursal,
            $cad,
            $sucursalModel->nombre,
            $sucursalModel->latitud,
            $sucursalModel->longitud
        );
    }

    public function recuperarInventario($Cadena, $idSuc, $nombreMedicamento){
        $inventarioModel = InventarioModel::with(['medicamento', 'sucursal', 'cadena'])
            ->where('id_cadena', $Cadena->getIdCadena())
            ->where('id_sucursal', $idSuc)
            ->whereHas('medicamento', function ($q) use ($nombreMedicamento) {
                $q->where('nombre', $nombreMedicamento);
            })
            ->first();
        return $this->transformarInventarioModelADomain($inventarioModel);
    }

    private function transformarInventarioModelADomain(InventarioModel $inventarioModel)
    {
        $sucursalDomain = $this->transformarSucursalModelADomain(       // estan bien los parametros?
            $inventarioModel->sucursal, $inventarioModel->cadena
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

    //recuperar receta por id en el metodo iran los parametros detalles, idReceta y la sucursal
    public function recuperarReceta($idReceta)
    {
        $recetaModel = RecetaModel::with([
            'detalle.medicamento',
            'detalle.lineasSurtido.sucursal',   // todos los detalles
            'sucursal', 
            'pago' // la sucursal destino
        ])
        ->where('id_receta', $idReceta)
        ->first();

        return $this->transformarRecetaModelADomain($recetaModel);
    }

   private function transformarPagoModelADomain($pagoModel): Pago
    {
        return new Pago(
            (float)$pagoModel->monto
        );
    }
    private function transformarDetallesModelADomain($detallesModel): array
    {
        $detalles = [];

        foreach ($detallesModel as $d) {

            // 1) Transformar las lineas_surtido de este detalle
            $lineasDomain = $this->transformarLineasSurtidoModelADomain($d->lineasSurtido);

            $medicamentoDomain = $this->transformarMedicamentoModelADomain($d->medicamento);

            // 2) Crear el DetalleReceta de dominio con su arreglo de línea de surtido
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

            $sucursalDomain = $this->transformarSucursalModelADomain($ls->sucursal);

            $lineas[] = new LineaSurtido(
                $sucursalDomain,
                $ls->estado_entrega,
                $ls->cantidad,
                // lo que tengas en tu clase LineaSurtido
            );
        }

        return $lineas;
    }

    private function transformarRecetaModelADomain(RecetaModel $recetaModel): Receta
    {
        // Sucursal de dominio
        $sucursalDomain = $this->transformarSucursalModelADomain(
            $recetaModel->sucursal
        );

        // Detalles de dominio (cada uno con sus líneas de surtido y sucursal)
        $detallesDomain = $this->transformarDetallesModelADomain(
            $recetaModel->detalle
        );

        // Pago de dominio (si existe)
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
                'recetas.detalle.lineasSurtido.sucursal', // si quieres toda la cascada
                'recetas.sucursal',
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
        // transformar recetas
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
        int $cantidadRequerida,
        int $limite = 20,
        float $radioKm = 20.0
    ): array {
        $idMed = $medicamento->getIdMedicamento();

        $lat0 = $sucursalOrigen->getLatitud();
        $lon0 = $sucursalOrigen->getLongitud();

        $table = (new SucursalModel())->getTable(); // 'sucursales'

        // 1) Bounding box
        $deltaLat = $radioKm / 111.0;
        $deltaLon = $radioKm / (111.0 * max(cos(deg2rad($lat0)), 0.00001));

        $latMin = $lat0 - $deltaLat;
        $latMax = $lat0 + $deltaLat;
        $lonMin = $lon0 - $deltaLon;
        $lonMax = $lon0 + $deltaLon;

        // 2) Haversine
        $haversine = "
            6371 * 2 * ASIN(
                SQRT(
                    POWER(SIN(RADIANS($table.latitud - ?) / 2), 2) +
                    COS(RADIANS(?)) * COS(RADIANS($table.latitud)) *
                    POWER(SIN(RADIANS($table.longitud - ?) / 2), 2)
                )
            )
        ";

        // 3) Consulta
        $query = SucursalModel::with('cadena')
            ->select("$table.*")
            ->selectRaw("$haversine AS distancia", [$lat0, $lat0, $lon0])
            ->whereBetween("$table.latitud",  [$latMin, $latMax])
            ->whereBetween("$table.longitud", [$lonMin, $lonMax])
            ->whereHas('inventarios', function ($q) use ($idMed) {
                $q->where('inventarios.id_medicamento', $idMed)
                ->where('inventarios.stock_actual', '>', 0);
            })
            ->orderBy('distancia', 'asc')
            ->limit($limite);

        // dd($query->toSql(), $query->getBindings());

        $sucursalesModel = $query->get();

        // 4) Transformar a dominio
        $resultado = [];

        foreach ($sucursalesModel as $sucModel) {
            $mismaSucursal = $sucModel->id_sucursal === $sucursalOrigen->getIdSucursal()
            && $sucModel->id_cadena   === $sucursalOrigen->getCadena()->getIdCadena();

            if ($mismaSucursal) {
                continue;
            }

            $resultado[] = $this->transformarSucursalModelADomain($sucModel);
        }
        return $resultado;
    }

}