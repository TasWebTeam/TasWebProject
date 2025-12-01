<?php

namespace App\Domain;
use App\Repositories\ConsultarRepository;
use App\Services\SucursalService;
use DateTime;
use InvalidArgumentException;

class Receta
{
    // --------- Atributos ---------
    private int $idReceta;
    private ?Sucursal $sucursal;
    private string $cedulaProfesional;
    private ?DateTime $fechaRegistro;
    private ?DateTime $fechaRecoleccion;
    private string $estadoPedido;
    private array $detallesReceta = [];
    private ?Pago $pago;

    // --------- Constructor ---------
    public function __construct(
        int $idReceta,
        ?Sucursal $sucursal,
        string $cedulaProfesional,
        ?DateTime $fechaRegistro,
        ?DateTime $fechaRecoleccion,
        string $estadoPedido = "",
        array $detallesReceta = [],
        Pago $pago
    ) {
        $this->idReceta = $idReceta;
        $this->sucursal = $sucursal;
        $this->cedulaProfesional = $cedulaProfesional;
        $this->fechaRegistro = $fechaRegistro;
        $this->fechaRecoleccion = $fechaRecoleccion;
        $this->estadoPedido = $estadoPedido;
        $this->setDetallesReceta($detallesReceta);
        $this->pago = $pago;
    }

    public static function nueva(): self
    {
        $pago = new Pago();
        return new self(
            0,
            null,
            '',
            null,
            null,
            '',
            [],
            $pago
        );
    }

    public function obtenerSucursal(string $nombreSucursal, string $nombreCadena ): void
    {
        $consultarRepository = new ConsultarRepository();               // Esta bien no indicar que se creó este objeto en el diagrama 2?
        $cad = $consultarRepository->recuperarCadena($nombreCadena);
        $suc = $consultarRepository->recuperarSucursal($nombreSucursal, $cad);
        $this->asignarSucursal($suc);
    }

    public function asignarSucursal($suc): void
    {
        $this->sucursal = $suc;
    }

    public function introducirCedulaProfesional(string $cedulaProfesional): void
    {
        $this->cedulaProfesional = $cedulaProfesional;
    }

    public function introducirMedicamento($nombreMedicamento, $cantidad)
    {
        
    }

    public function crearDetalleReceta(string $nombreMedicamento, int $cantidad): void
    {
        $inv = $this->sucursal->obtenerInventario($nombreMedicamento);
        $med = $inv->obtenerMedicamento();
        $precio = $inv->obtenerPrecio();
        $dr = new DetalleReceta($med, $cantidad, $precio);
        $this->agregarDetalleReceta($dr);
        $total = $this->calcularTotal();
        $comisionTotal = $this->calcularComision($total);
        $this->pago->actualizarComision($comisionTotal);
        // lógica de dominio pendiente
    }

    public function calcularTotal(): float
    {
        $total = 0;
        foreach ($this->detallesReceta as $detalle) {
            $st = $detalle->obtenerSubtotal();
            $total += $st;
        }
        return $total;
    }

    public function calcularComision($total): float
    {
        return $total * 0.15;
    }

    public function procesarReceta(int $numTarjeta, SucursalService $sucursalService): void
    {
        foreach ($this->detallesReceta as $detalle) {
            // $this->sucursal->procesar($this->sucursal, $t);
            $detalle->procesar($this->sucursal, $sucursalService);
        }
        $this->pago->validarPago($numTarjeta);
        $this->calcularFecha();
        // lógica de dominio pendiente
    }

    public function calcularFecha(): void
    {
            // 1) Fecha de registro = ahora
        $fechaRegistro = new DateTime();
        $this->setFechaRegistro($fechaRegistro);

        // ===============================
        // 2) Calcular tiempo estimado
        // ===============================

        $tiempoMinutos = 0;

        // Tiempo base por validaciones, captura, etc.
        $tiempoMinutos += 10; // puedes ajustar

        $sucursalesInvolucradas = [];

        // Recorrer detalles y sus líneas de surtido
        foreach ($this->detallesReceta as $detalle) {
            foreach ($detalle->getLineasSurtido() as $linea) {

                // 2.1 Tiempo por cada línea de surtido
                $tiempoMinutos += 5; // ej. 5 min por línea

                // 2.2 Registrar sucursal involucrada
                $sucursal = $linea->getSucursal();
                $idSuc    = $sucursal->getIdSucursal();

                $sucursalesInvolucradas[$idSuc] = true;
            }
        }

        // 2.3 Tiempo por cada sucursal diferente que participa
        $tiempoPorSucursal = 30; // ej. 30 min por traslados / coordinación
        $tiempoMinutos += count($sucursalesInvolucradas) * $tiempoPorSucursal;

        // Crear fecha de recolección sumando el tiempo estimado
        $fechaRecoleccion = (clone $fechaRegistro)->modify("+{$tiempoMinutos} minutes");

        // ============================================
        // 3) Ajustar a horario laboral (9:00 - 20:00)
        // ============================================

        $fechaRecoleccion = $this->ajustarAHorarioLaboral($fechaRecoleccion, 9, 20);

        $this->setFechaRecoleccion($fechaRecoleccion);
        // lógica de dominio pendiente
    }
    private function ajustarAHorarioLaboral(DateTime $fecha, int $horaInicio = 9, int $horaFin = 20): DateTime
    {
                    /**
         * Ajusta una fecha al horario laboral:
         * - Si cae antes de la hora de apertura, la mueve a la hora de apertura.
         * - Si cae después del cierre, la mueve al siguiente día a la hora de apertura.
         */
        // Clonamos por seguridad (por si no quieres mutar el original)
        $ajustada = clone $fecha;

        $hora   = (int)$ajustada->format('H');
        $minuto = (int)$ajustada->format('i');

        // Caso 1: antes de abrir
        if ($hora < $horaInicio) {
            $ajustada->setTime($horaInicio, 0);
            return $ajustada;
        }

        // Caso 2: después de cerrar (o exactamente a la hora de cierre pero con minutos > 0)
        if ($hora > $horaFin || ($hora === $horaFin && $minuto > 0)) {
            // Pasar al siguiente día a la hora de apertura
            $ajustada->modify('+1 day')->setTime($horaInicio, 0);
            return $ajustada;
        }

        // Caso 3: dentro del horario → se respeta la hora calculada
        return $ajustada;
    }

    public function devolverMedicamentos(): void
    {
        // lógica de dominio pendiente
    }

    public function cambiarEstado(string $estado): void
    {
        // lógica de dominio pendiente
    }

    public function agregarDetalleReceta($detalle){
        $this->detallesReceta[] = $detalle;
    }


    public function getIdReceta(): int
    {
        return $this->idReceta;
    }

    public function getSucursal(): Sucursal
    {
        return $this->sucursal;
    }
    public function getCedulaProfesional(): string
    {
        return $this->cedulaProfesional;
    }

    public function getFechaRegistro(): DateTime
    {
        return $this->fechaRegistro;
    }

    public function getFechaRecoleccion(): DateTime
    {
        return $this->fechaRecoleccion;
    }

    public function getEstadoPedido(): string
    {
        return $this->estadoPedido;
    }

    public function getDetallesReceta(): array
    {
        return $this->detallesReceta;
    }

    public function setIdReceta(int $idReceta): void
    {
        $this->idReceta = $idReceta;
    }


    public function setFechaRegistro(DateTime $fechaRegistro): void
    {
        $this->fechaRegistro = $fechaRegistro;
    }

    public function setFechaRecoleccion(DateTime $fechaRecoleccion): void
    {
        $this->fechaRecoleccion = $fechaRecoleccion;
    }

    public function setEstadoPedido(string $estadoPedido): void
    {
        $this->estadoPedido = $estadoPedido;
    }

    public function setDetallesReceta(array $detallesReceta): void{
        foreach ($detallesReceta as $d) {
            if (!$d instanceof DetalleReceta) {
                throw new InvalidArgumentException("Todos los elementos deben ser DetalleReceta");
            }
        }
        $this->detallesReceta = $detallesReceta;
    }

}