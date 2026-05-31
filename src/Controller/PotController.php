<?php

namespace App\Controller;

use App\Entity\Pot;
use App\Form\PotType;
use App\Repository\PotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/pot')]
final class PotController extends AbstractController
{
    #[Route(name: 'app_pot_index', methods: ['GET'])]
    public function index(PotRepository $potRepository): Response
    {
        return $this->render('pot/index.html.twig', [
            'pots' => $potRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pot_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pot = new Pot();
        $form = $this->createForm(PotType::class, $pot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pot);
            $entityManager->flush();

            return $this->redirectToRoute('app_pot_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pot/new.html.twig', [
            'pot' => $pot,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pot_show', methods: ['GET'])]
    public function show(Pot $pot): Response
    {
        return $this->render('pot/show.html.twig', [
            'pot' => $pot,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pot_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Pot $pot, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PotType::class, $pot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pot_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pot/edit.html.twig', [
            'pot' => $pot,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pot_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Pot $pot, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pot->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($pot);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pot_index', [], Response::HTTP_SEE_OTHER);
    }
}
