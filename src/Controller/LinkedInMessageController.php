<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LinkedInMessageController extends AbstractController
{
    #[Route('/linked/in/message', name: 'app_linked_in_message')]
    public function index(): Response
    {
        return $this->render('linkedin_message/show.html.twig', [
            'controller_name' => 'LinkedInMessageController',
        ]);
    }
}
