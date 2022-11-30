<?php

namespace App\Controller;

use App\services\AdvertisingService;
use App\services\CityService;
use App\services\ExampleService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app.home')]
    public function index(
        AdvertisingService $advertisingService,
    ): Response
    {
        $smallAd = $advertisingService->getAd();
        //dump($smallAd);
        //die;
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'smallAd' => $smallAd,
        ]);

    }

    #[Route('/annonce/{id}', name: 'ads.display.simple', requirements: ['id' => '^\d+'])]
    public function displaySimple(
        ExampleService $exampleService,
        CityService $cityService,
        int $id
    ): Response {
        $seller = $exampleService->getSeller();
        //$cityInfos = $cityService->getCity($seller['cp'], $seller['city']);

        //dump($seller);
        //die;

        return $this->render('default/ad.display.html.twig', [
            'controller_name' => 'DefaultController',
            'seller' => $seller,
            //'cityInfos' => $cityInfos,
        ]);
    }

    #[Route('/annonce/{cat}/{id}', name: 'ads.display', requirements: ['id' => '^\d+', 'cat' => '[a-z][a-z0-9_-]+'])]
    public function display(string $cat, int $id): Response
    {
        return $this->render('default/ad.display.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}