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
    private Sucursal $sucursal;
    private string $cedulaProfesional;
    private DateTime $fechaRegistro;
    private DateTime $fechaRecoleccion;
    private string $estadoPedido;
    private array $detallesReceta = [];
    private Pago $pago;

    // --------- Constructor ---------
    public function __construct(
        int $idReceta,
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
            null
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
        // lógica de dominio pendiente
    }

    public function calcularFecha(): void
    {
        // lógica de dominio pendiente
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