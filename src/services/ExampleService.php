<?php

namespace App\services;

class ExampleService
{
    /**
     * @var CityService
     */
    protected CityService $cityService;

    public function __construct(
        CityService $cityService
    ) {
        $this->cityService = $cityService;
    }

    /**
     * return a random seller
     *
     * @return string
     * @throws \Exception
     */
    public function getSeller(): array
    {
        $names = [
            [
                'name' => 'James',
                'tel' => '0102030405',
                'cp' => '15000',
                'city' => 'Aurillac',
            ],
            [
                'name' => 'Connor',
                'tel' => '0102030405',
                'cp' => '15000',
                'city' => 'Aurillac',
            ],
            [
                'name' => 'Andrew',
                'tel' => '0102030405',
                'cp' => '15100',
                'city' => 'Saint-Flour',
            ],
            [
                'name' => 'Phil',
                'tel' => '0102030405',
                'cp' => '63000',
                'city' => 'Clermond-Ferrand',
            ],
        ];
        $seller = $names[random_int(0, count($names) - 1)];

        $seller['city_data'] = $this->cityService->getCity($seller['cp'], $seller['city']);

        return $seller;
    }
}