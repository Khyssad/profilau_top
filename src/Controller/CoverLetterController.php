<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CoverLetterController extends AbstractController
{
    #[Route('/cover/letter', name: 'app_cover_letter')]
    public function index(): Response
    {
        return $this->render('cover_letter/show.html.twig', [
            'controller_name' => 'CoverLetterController',
        ]);
    }
}
