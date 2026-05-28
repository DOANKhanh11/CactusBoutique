<?php

namespace App\Controller;

use App\Entity\Cactus;
use App\Form\CactusType;
use App\Repository\CactusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cactus')]
final class CactusController extends AbstractController
{
    #[Route(name: 'app_cactus_index', methods: ['GET'])]
    public function index(CactusRepository $cactusRepository): Response
    {
        return $this->render('cactus/index.html.twig', [
            'cacti' => $cactusRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_cactus_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cactu = new Cactus();
        $form = $this->createForm(CactusType::class, $cactu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cactu);
            $entityManager->flush();

            return $this->redirectToRoute('app_cactus_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cactus/new.html.twig', [
            'cactu' => $cactu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cactus_show', methods: ['GET'])]
    public function show(Cactus $cactu): Response
    {
        return $this->render('cactus/show.html.twig', [
            'cactu' => $cactu,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cactus_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cactus $cactu, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CactusType::class, $cactu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cactus_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cactus/edit.html.twig', [
            'cactu' => $cactu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cactus_delete', methods: ['POST'])]
    public function delete(Request $request, Cactus $cactu, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cactu->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($cactu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cactus_index', [], Response::HTTP_SEE_OTHER);
    }
}
