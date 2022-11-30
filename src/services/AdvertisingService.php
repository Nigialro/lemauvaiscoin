<?php

namespace App\services;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class AdvertisingService
{

    public function getAd(): array
    {

        $ads = [
            [
                'adImg' => "asset('../../../public/assets/concert01.png')",
                'name' => "ad01",
            ],
            [
                'adImg' => "asset('../../../public/assets/concert02.png')",
                'name' => "ad02",
            ],
            [
                'adImg' => "asset('../../../public/assets/concert03.png')",
                'name' => "ad03",
            ],
            [
                'adImg' => "asset('../../../public/assets/concert04.png')",
                'name' => "ad04",
            ],
        ];

        $smallAd = $ads[random_int(0, count($ads) - 1)];

        return $smallAd;

    }
}