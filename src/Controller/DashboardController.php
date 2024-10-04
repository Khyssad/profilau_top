<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        // Simuler des données pour le dashboard
        $dashboardData = [
            'totalApplications' => 15,
            'pendingApplications' => 5,
            'interviewsScheduled' => 3,
            'offersMade' => 1,
            'recentApplications' => [
                ['company' => 'TechCorp', 'position' => 'Développeur Full Stack', 'date' => '2024-03-01'],
                ['company' => 'InnovSoft', 'position' => 'Ingénieur DevOps', 'date' => '2024-02-28'],
                ['company' => 'DataViz', 'position' => 'Data Scientist', 'date' => '2024-02-25'],
            ],
            'upcomingInterviews' => [
                ['company' => 'TechCorp', 'date' => '2024-03-10', 'time' => '14:00'],
                ['company' => 'InnovSoft', 'date' => '2024-03-15', 'time' => '10:30'],
            ],
        ];

        return $this->render('dashboard/index.html.twig', [
            'dashboardData' => $dashboardData,
        ]);
    }
}