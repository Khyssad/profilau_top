<?php

namespace App\Controller;

use App\Entity\JobOffer;
use App\Form\JobOfferType;
use App\Repository\JobOfferRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

class JobOfferController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/job/offer', name: 'app_job_offer')]
    public function index(JobOfferRepository $jobOfferRepository): Response
    {
        $jobOffers = $jobOfferRepository->findAll();

        return $this->render('job_offer/list.html.twig', [
            'job_offer' => $jobOffers,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/job-offers/new', name: 'app_job_offer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $jobOffer = new JobOffer();
        $form = $this->createForm(JobOfferType::class, $jobOffer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $jobOffer->setAppUser($this->getUser());
            $entityManager->persist($jobOffer);
            $entityManager->flush();

            $this->addFlash('success', 'Offre d\'emploi créée avec succès.');
            return $this->redirectToRoute('app_job_offer');
        }

        return $this->render('job_offer/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/job-offers/{id}', name: 'app_job_offer_show', methods: ['GET'])]
    public function show(JobOffer $jobOffer): Response
    {
        return $this->render('job_offer/show.html.twig', [
            'job_offer' => $jobOffer,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/job-offers/{id}/edit', name: 'app_job_offer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, JobOffer $jobOffer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(JobOfferType::class, $jobOffer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'L\'offre d\'emploi a été mise à jour avec succès.');
            return $this->redirectToRoute('app_job_offer_show', ['id' => $jobOffer->getId()]);
        }

        return $this->render('job_offer/edit.html.twig', [
            'job_offer' => $jobOffer,
            'form' => $form->createView(),
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/job-offers/{id}/delete', name: 'app_job_offer_delete', methods: ['POST'])]
    public function delete(Request $request, JobOffer $jobOffer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$jobOffer->getId(), $request->request->get('_token'))) {
            $entityManager->remove($jobOffer);
            $entityManager->flush();
            $this->addFlash('success', 'L\'offre d\'emploi a été supprimée avec succès.');
        }

        return $this->redirectToRoute('app_job_offer', [], Response::HTTP_SEE_OTHER);
    }
}