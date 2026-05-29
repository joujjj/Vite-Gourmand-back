<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class DeliveryService
{
    private const BASE_ADDRESS  = '1 rue du Chef, 33000 Bordeaux';
    private const FORFAIT        = 5.00;
    private const PRIX_KM        = 0.59;
    private const FREE_CITY      = 'bordeaux';

    public function __construct(
        private HttpClientInterface $httpClient,
        private string              $orsApiKey,
    ) {}

    /**
     * Calcule les frais de livraison.
     * Retourne 0.00 si la ville est Bordeaux.
     * Sinon : 5€ + 0,59€/km (distance routière depuis le dépôt).
     */
    public function calculerFrais(string $adresse, string $ville, string $cp): array
    {
        if (strtolower(trim($ville)) === self::FREE_CITY) {
            return [
                'frais'    => 0.00,
                'distance' => 0,
                'gratuit'  => true,
            ];
        }

        try {
            $adresseComplete = "{$adresse}, {$cp} {$ville}, France";
            $distance        = $this->getDistanceKm(self::BASE_ADDRESS, $adresseComplete);
            $frais           = self::FORFAIT + (self::PRIX_KM * $distance);

            return [
                'frais'    => round($frais, 2),
                'distance' => $distance,
                'gratuit'  => false,
            ];
        } catch (\Exception $e) {
            // En cas d'erreur API, on applique le forfait seul
            return [
                'frais'    => self::FORFAIT,
                'distance' => 0,
                'gratuit'  => false,
                'error'    => 'Distance non calculée, forfait appliqué.',
            ];
        }
    }

    /**
     * Géocode une adresse et retourne [longitude, latitude].
     */
    private function geocode(string $adresse): array
    {
        $response = $this->httpClient->request('GET', 'https://api.openrouteservice.org/geocode/search', [
            'query' => [
                'api_key' => $this->orsApiKey,
                'text'    => $adresse,
                'size'    => 1,
            ],
        ]);

        $data = $response->toArray();

        if (empty($data['features'])) {
            throw new \RuntimeException("Adresse introuvable : {$adresse}");
        }

        return $data['features'][0]['geometry']['coordinates']; // [lng, lat]
    }

    /**
     * Calcule la distance routière en km entre deux adresses.
     */
    private function getDistanceKm(string $from, string $to): float
    {
        $coordFrom = $this->geocode($from);
        $coordTo   = $this->geocode($to);

        $response = $this->httpClient->request('POST', 'https://api.openrouteservice.org/v2/directions/driving-car', [
            'headers' => [
                'Authorization' => $this->orsApiKey,
                'Content-Type'  => 'application/json',
            ],
            'json' => [
                'coordinates' => [$coordFrom, $coordTo],
            ],
        ]);

        $data = $response->toArray();

        // Distance en mètres → kilomètres
        $distanceMetres = $data['routes'][0]['summary']['distance'] ?? 0;

        return round($distanceMetres / 1000, 1);
    }
}
