<?php

namespace App\Domain;
use App\Repositories\ConsultarRepository;
use App\Services\SucursalService;
use DateTime;
use Exception;
use InvalidArgumentException;

class Receta
{
    private int $idReceta;
    private ?Sucursal $sucursal;
    private string $cedulaProfesional;
    private ?DateTime $fechaRegistro;
    private ?DateTime $fechaRecoleccion;
    private string $estadoPedido;
    private array $detallesReceta = [];
    private ?Pago $pago;

    public function __construct(
        int $idReceta = 0,
        ?Sucursal $sucursal,
        string $cedulaProfesional,
        ?DateTime $fechaRegistro,
        ?DateTime $fechaRecoleccion,
        string $estadoPedido = "",
        array $detallesReceta = [],
        ?Pago $pago
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
        return new self(
            0,
            null,
            '',
            null,
            null,
            '',
            [],
            new Pago()
        );
    }

    public function asignarSucursal($suc): void
    {
        $this->sucursal = $suc;
    }

    public function introducirCedulaProfesional(string $cedulaProfesional): void
    {
        $this->cedulaProfesional = $cedulaProfesional;
    }

    public function crearDetalleReceta(Medicamento $med, int $cantidad, float $precio): void
    {
        $dr = new DetalleReceta($med, $cantidad, $precio);
        $this->agregarDetalleReceta($dr);
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

    public function obtenerPago(): Pago 
    {
        return $this->pago;
    }

    public function calcularFecha(): void
    {
        $fechaRegistro = new DateTime();
        $this->setFechaRegistro($fechaRegistro);

        $tiempoMinutos = 10; 
        $sucursalesInvolucradas = [];

        foreach ($this->detallesReceta as $detalle) {
            foreach ($detalle->getLineasSurtido() as $linea) {
                $tiempoMinutos += 5;

                $sucursal = $linea->getSucursal();
                $idSuc    = $sucursal->getIdSucursal();
                $sucursalesInvolucradas[$idSuc] = true;
            }
        }

        $tiempoPorSucursal = 30;
        $tiempoMinutos += count($sucursalesInvolucradas) * $tiempoPorSucursal;

        $fechaRecoleccion = (clone $fechaRegistro)->modify("+{$tiempoMinutos} minutes");
        $fechaRecoleccion = $this->ajustarAHorarioLaboral($fechaRecoleccion, 9, 20);

        $this->setFechaRecoleccion($fechaRecoleccion);
    }

    private function ajustarAHorarioLaboral(
        DateTime $fecha,
        int $horaInicio = 9,
        int $horaFin = 20
    ): DateTime {
        $ajustada = clone $fecha;

        $hora   = (int)$ajustada->format('H');
        $minuto = (int)$ajustada->format('i');

        if ($hora < $horaInicio) {
            $ajustada->setTime($horaInicio, 0);
            return $ajustada;
        }

        if ($hora > $horaFin || ($hora === $horaFin && $minuto > 0)) {
            $ajustada->modify('+1 day')->setTime($horaInicio, 0);
            return $ajustada;
        }

        return $ajustada;
    }

    public function cambiarEstado(string $estado): void
    {
        $this->estadoPedido = $estado;
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

    public function getPago(): ?Pago
    {
        return $this->pago;
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

    public function setPago(?Pago $pago): void
    {
        $this->pago = $pago;
    }
}