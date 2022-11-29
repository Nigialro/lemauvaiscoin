<?php

namespace App\services;

// API postal to INSEE : https://api.gouv.fr/documentation/api_carto_codes_postaux
// API INSEE to data : https://api.gouv.fr/documentation/api-geo
// EP =================== /commune/{code}

// HTPP CLIENT
// https://symfony.com/doc/current/http_client.html

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CityService
{

    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getCity(string $postalCode): array
    {
        // on check si le nom de la ville correspond a un des element de la reponse
        // de la premiére API, si non on prend le premier element du tableau
        // $resp[0]

        $ukn = [
            'city' => 'unknown',
            'cp' => 'unknown',
            'lat' => '0',
            'lon' => '0',
            'departement' => 'unknown',
        ];
        $response = $this->client->request(
            'GET',
            'https://geo.api.gouv.fr/communes?codePostal=$postalCode'
        );

        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $contentType = $response->getHeaders()['application/json'][0];
        // $contentType = 'application/json'
        $content = $response->getContent();
        // $content = '{
        //    "nom": "Versailles",
        //    "code": "78646",
        //    "codeDepartement": "78",
        //    "siren": "217806462",
        //    "codeEpci": "247800584",
        //    "codeRegion": "11",
        //    "codesPostaux": [
        //      "78000"
        //    ],
        //    "population": 84808
        //  }'
        $content = $response->toArray();
        // $content = ['nom' => 'Versailles'', 'code' => '78645', ...]

        return $content ?? $ukn;
    }

}