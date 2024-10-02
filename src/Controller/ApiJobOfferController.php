<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiJobOfferController extends AbstractController
{
    #[Route('/api/job/offer', name: 'app_api_job_offer')]
    public function index(): Response
    {
        return $this->render('api_job_offer/index.html.twig', [
            'controller_name' => 'ApiJobOfferController',
        ]);
    }
}
