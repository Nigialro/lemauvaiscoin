<?php

namespace App\Controller;

use App\services\ExampleService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app.home')]
    public function index(): Response
    {

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    #[Route('/annonce/{id}', name: 'ads.display.simple', requirements: ['id' => '^\d+'])]
    public function displaySimple(
        ExampleService $exampleService,
        int $id
    ): Response {
        $seller = $exampleService->getSeller();

        dump($seller);
        die;

        return $this->render('default/ad.display.html.twig', [
            'controller_name' => 'DefaultController',
            'seller' => $seller,
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