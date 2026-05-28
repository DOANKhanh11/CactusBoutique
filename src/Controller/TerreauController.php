<?php

namespace App\Controller;

use App\Entity\Terreau;
use App\Form\TerreauType;
use App\Repository\TerreauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/terreau')]
final class TerreauController extends AbstractController
{
    #[Route(name: 'app_terreau_index', methods: ['GET'])]
    public function index(TerreauRepository $terreauRepository): Response
    {
        return $this->render('terreau/index.html.twig', [
            'terreaus' => $terreauRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_terreau_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $terreau = new Terreau();
        $form = $this->createForm(TerreauType::class, $terreau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($terreau);
            $entityManager->flush();

            return $this->redirectToRoute('app_terreau_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('terreau/new.html.twig', [
            'terreau' => $terreau,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_terreau_show', methods: ['GET'])]
    public function show(Terreau $terreau): Response
    {
        return $this->render('terreau/show.html.twig', [
            'terreau' => $terreau,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_terreau_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Terreau $terreau, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TerreauType::class, $terreau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_terreau_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('terreau/edit.html.twig', [
            'terreau' => $terreau,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_terreau_delete', methods: ['POST'])]
    public function delete(Request $request, Terreau $terreau, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$terreau->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($terreau);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_terreau_index', [], Response::HTTP_SEE_OTHER);
    }
}
