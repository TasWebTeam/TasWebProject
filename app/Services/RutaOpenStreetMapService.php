<?php
namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class RutaOpenStreetMapService
{
    private Client $http;

    public function __construct()
    {
        $this->http = new Client([
            'base_uri' => env('OSM_ROUTING_BASE_URL'), // ej: 'https://api.openrouteservice.org/'
            'timeout'  => 10.0,
        ]);
    }

    /**
     * Calcula la distancia en km entre dos puntos usando tu API de rutas OSM.
     */
    public function obtenerDistanciaKm(float $latOrigen, float $lonOrigen, float $latDestino, float $lonDestino): ?float
    {
        try {
            $response = $this->http->get('v2/directions/driving-car', [
                'query' => [
                    'api_key' => env('OSM_ROUTING_API_KEY'),
                    'start'   => $lonOrigen . ',' . $latOrigen,   // muchas APIs usan lon,lat
                    'end'     => $lonDestino . ',' . $latDestino,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            $metros = $data['features'][0]['properties']['summary']['distance'] ?? null;

            if ($metros === null) {
                return null;
            }

            return $metros / 1000.0; // a kilómetros
        } catch (\Throwable $e) {
            Log::error('Error consultando ruta OSM: ' . $e->getMessage());
            return null;
        }
    }
}