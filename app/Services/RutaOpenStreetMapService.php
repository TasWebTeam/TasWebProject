<?php
namespace App\Services;

use GuzzleHttp\Client;

class RutaOpenStreetMapService
{
    private Client $http;

    public function __construct()
    {
        $this->http = new Client([
            'base_uri' => env('OSM_ROUTING_BASE_URL'), 
            'timeout'  => 10.0,
        ]);
    }


    public function obtenerDistanciaKm(float $latOrigen, float $lonOrigen, float $latDestino, float $lonDestino): ?float
    {
        try {
            $response = $this->http->get('v2/directions/driving-car', [
                'query' => [
                    'api_key' => env('OSM_ROUTING_API_KEY'),
                    'start'   => $lonOrigen . ',' . $latOrigen,  
                    'end'     => $lonDestino . ',' . $latDestino,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            $metros = $data['features'][0]['properties']['summary']['distance'] ?? null;

            if ($metros === null) {
                return null;
            }

            return $metros / 1000.0;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function obtenerRutaCoordenadas(
        float $latOrigen,
        float $lonOrigen,
        float $latDestino,
        float $lonDestino
    ): array {
        try {
            $response = $this->http->get('v2/directions/driving-car', [
                'query' => [
                    'api_key' => env('OSM_ROUTING_API_KEY'),
                    'start'   => $lonOrigen . ',' . $latOrigen,
                    'end'     => $lonDestino . ',' . $latDestino,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            $coords = $data['features'][0]['geometry']['coordinates'] ?? [];

            $puntos = [];

            foreach ($coords as $par) {
                $puntos[] = [
                    'lat' => $par[1],
                    'lng' => $par[0],
                ];
            }

            return $puntos;

        } catch (\Throwable $e) {
            return [];
        }
    }
}