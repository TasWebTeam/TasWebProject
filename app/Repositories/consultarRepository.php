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

    //recuperar receta por id en el metodo iran los parametros detalles, idReceta y sucursal
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
        float $radioKm = 20.0   // radio aproximado de búsqueda
    ): array {
        $idCadenaOrigen = $sucursalOrigen->getCadena()->getIdCadena();
        $idMed          = $medicamento->getIdMedicamento();

        $lat0 = $sucursalOrigen->getLatitud();
        $lon0 = $sucursalOrigen->getLongitud();

        // ====== 1) BOUNDING BOX (filtro geográfico aproximado) ======
        // 1° lat ~ 111 km
        $deltaLat = $radioKm / 111.0;

        // 1° lon ~ 111 * cos(lat) km
        $deltaLon = $radioKm / (111.0 * cos(deg2rad($lat0)));

        $latMin = $lat0 - $deltaLat;
        $latMax = $lat0 + $deltaLat;
        $lonMin = $lon0 - $deltaLon;
        $lonMax = $lon0 + $deltaLon;

        // ====== 2) Fórmula Haversine en SQL (distancia en km) ======
        $haversine = "
            6371 * 2 * ASIN(
                SQRT(
                    POWER(SIN(RADIANS(sucursales.latitud - ?) / 2), 2) +
                    COS(RADIANS(?)) * COS(RADIANS(sucursales.latitud)) *
                    POWER(SIN(RADIANS(sucursales.longitud - ?) / 2), 2)
                )
            )
        ";

        // ====== 3) Consulta: cadena + bounding box + tiene medicamento + stock > 0 ======
        $sucursalesModel = SucursalModel::with('cadena')
            ->select('sucursales.*')
            ->selectRaw("$haversine AS distancia", [$lat0, $lat0, $lon0])
            ->where('id_cadena', $idCadenaOrigen)
            ->whereBetween('latitud', [$latMin, $latMax])
            ->whereBetween('longitud', [$lonMin, $lonMax])
            ->whereHas('medications', function ($q) use ($idMed /*, $cantidadRequerida*/) {
                $q->where('medicamentos.id_medicamento', $idMed)
                ->wherePivot('stock_actual', '>', 0);
                // Si quieres que pueda surtir TODO:
                // ->wherePivot('stock_actual', '>=', $cantidadRequerida);
            })
            ->orderBy('distancia', 'asc') // las más cercanas primero
            ->limit($limite)              // y solo las N más cercanas
            ->get();

        // ====== 4) Transformar a dominio ======
        $resultado = [];

        foreach ($sucursalesModel as $sucModel) {
            // evitar la misma sucursal origen si no quieres re-usarla
            if ($sucModel->id_sucursal === $sucursalOrigen->getIdSucursal()) {
                continue;
            }

            $resultado[] = $this->transformarSucursalModelADomain($sucModel);
        }

        return $resultado;
    }
}