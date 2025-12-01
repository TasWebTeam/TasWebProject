<?php

namespace App\Domain;
use App\repositories\ConsultarRepository;
use App\Repositories\ActualizarRepository;
class Sucursal
{
    private int $id;
    private int $idSucursal;
    private Cadena $cadena;
    private string $nombre;
    private float $latitud;
    private float $longitud;

    public function __construct(
        int $id = 0,
        int $id_sucursal = 0,
        ?Cadena $cadena = null,
        string $nombre = "",
        float $latitud = 0.0,
        float $longitud = 0.0
    ) {
        $this->id = $id;
        $this->idSucursal = $id_sucursal;
        $this->cadena = $cadena;
        $this->nombre = $nombre;
        $this->latitud = $latitud;
        $this->longitud = $longitud;
    }
    
    public function obtenerInventario(string $nombreMedicamento): InventarioSucursal{
        $consultarRepository = new ConsultarRepository();       // NO SE INDICA EN EL DIAGRAMA
        $inv = $consultarRepository->recuperarInventario($this->getCadena(), $this->getId(), $nombreMedicamento);   // CAMBIO DE GETIDSUCURSAL
        return $inv;
    }

    public function verificarDisponibilidad(int $cant, Medicamento $med): int{
        //Nota: Modificar el diagrama de interaccion
        $inv = $this->obtenerInventario($med->getNombre());
        $stockExistente = $inv->obtenerStock();
        $cantObtenida = 0;

        if ($stockExistente>0) {       // ASI SE EVITA UNA LLAMADA INNECESARIA             
            $actualizarRepository = new ActualizarRepository();
            if ($stockExistente >= $cant) {
                $inv->descontarMedicamento($cant);
                $cantObtenida = $cant;     
            } else {
                $inv->descontarMedicamento($stockExistente);
                $cantObtenida = $stockExistente;
            }
            $idSucursal = $this->getIdSucursal();
            $cadena = $this->getCadena();
            $actualizarRepository->actualizarInventario($cadena, $idSucursal, $inv);
        }
        return $cantObtenida;
    }

    public function devolverReceta(int $idReceta): void{

    }

    public function confirmarRecetaNoRecogida(int $idReceta, string $estado): void{

    }

    public function getId(): int {
        return $this->id;
    }

    public function getIdSucursal(): int { 
        return $this->idSucursal; 
    }

    public function getCadena(): Cadena { 
        return $this->cadena; 
    }

    public function getNombre(): string { 
        return $this->nombre; 
    }

    public function getLatitud(): float {
         return $this->latitud; 
    }

    public function getLongitud(): float { 
        return $this->longitud; 
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setIdSucursal(int $idSucursal): void { 
        $this->idSucursal = $idSucursal; 
    }

    public function setCadena(?Cadena $cadena): void {
         $this->cadena = $cadena;
    }

    public function setNombre(string $nombre): void {
         $this->nombre = $nombre; 
    }

    public function setLatitud(float $latitud): void {
         $this->latitud = $latitud; 
    }

    public function setLongitud(float $longitud): void { 
        $this->longitud = $longitud; 
    }

    public function toArray(): array
    {
        return [
            'idSucursal' => $this->idSucursal,
            'cadena'      => $this->cadena->toArray(),
            'nombre'      => $this->nombre,
            'latitud'     => $this->latitud,
            'longitud'    => $this->longitud,
        ];
    }
}