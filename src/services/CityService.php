<?php

namespace App\services;

//use App\services\Contracts\HttpClient;


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
     * App\services\Contracts\HttpClient;
     */
    private HttpClientInterface $client;


    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;

    }

    public function normalizer(string $input): string
    {
        // Aurillac => aurillac => auRillac => aurillaC
        // Saint-Flour => Saint Flour => saint flour => saint,flour => saint-flour
        //enlever les accent, passer tout en strtolower, remove [a-z0-9] => ' '

        $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', ' ', '-', '_', "'", '/', ',');
        $replace = array('a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', '', '', '', '', 'sur', '');
        $input = str_replace($search, $replace, $input);

        return $input;
    }

    public function getCity(string $postalCode, string $city): array
    {
        $response = $this->client->request(
            'GET',
            'https://apicarto.ign.fr/api/codes-postaux/communes/' . $postalCode
        );
        //  'https://geo.api.gouv.fr/communes?codePostal=$postalCode'

        //$statusCode = $response->getStatusCode();
        // $statusCode = 200
        //$contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json'
        //$content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $contentData = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        if(!is_array($contentData)){
            throw new \RuntimeException('bad format data');
        }


        $cityFound = null;
        $normalizedCity = $this->normalizer($city);
        //foreach $contentData et check si on a la ville, si on trouve alors on stock dans $cityFound
        // foreach
        foreach($contentData as $ligne) {
            $normalizedData = $this->normalizer($ligne['nomCommune']);
            if($normalizedCity == $normalizedData) {
                $cityFound = $ligne;
            }
        }
        // ensuite
        if($cityFound === null){
            $cityFound = $contentData[0] ?? null;
        }

        if($cityFound === null){
            throw new \RuntimeException('no data found');
        }

        $codeInsee = $cityFound['codeCommune'];

        $urlApiCommune = 'https://geo.api.gouv.fr/communes/' . $codeInsee
            . '?fields=nom,code,codesPostaux,siren,codeEpci,codeDepartement,codeRegion,population,departement&format=json&geometry=centre';

        ///2eme call API

        $response2 = $this->client->request(
            'GET',
            $urlApiCommune
        );


        $contentData2 = $response2->toArray();

        //"https://geo.api.gouv.fr/communes/$codeInsee"

        if(!is_array($contentData2)){
            throw new \RuntimeException('bad format data');
        }

        // todo si jamais la reponse est bonne alors on return la reponse de l'api

        //si non alors on return UKN

        $ukn = [
            'city' => 'unknown',
            'cp' => 'unknown',
            'departement' => 'unknown',
        ];
        $cityInfos = [
                'city' => $contentData2['nom'],
                'cp' => $contentData2['codesPostaux'],
                'departement' => $contentData2['codeDepartement'],
        ];

        return $cityInfos ?? $ukn;

    }


}