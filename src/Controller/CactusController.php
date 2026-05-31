<?php

namespace App\Controller;

use App\Entity\Cactus;
use App\Form\CactusSearchType;
use App\Form\CactusType;
use App\Repository\CactusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/cactus')]
final class CactusController extends AbstractController
{
    #[Route(name: 'app_cactus_index', methods: ['GET'])]
    public function index(Request $request, CactusRepository $cactusRepository): Response
    {
        $form = $this->createForm(CactusSearchType::class);
        $form->handleRequest($request);

        $criteria = $form->isSubmitted() && $form->isValid()
            ? $form->getData()
            : [];

        return $this->render('cactus/index.html.twig', [
            'cacti' => $cactusRepository->search($criteria),
            'searchForm' => $form,
        ]);
    }

    #[Route('/new', name: 'app_cactus_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $cactu = new Cactus();
        $form = $this->createForm(CactusType::class, $cactu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cactu->setVendeur($this->getUser());
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

    #[Route('/{id}/favorite', name: 'app_cactus_favorite', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function favorite(Request $request, Cactus $cactu, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('favorite'.$cactu->getId(), $token)) {
            return $this->redirectToRoute('app_cactus_show', ['id' => $cactu->getId()]);
        }

        $user = $this->getUser();
        if (!$user instanceof \App\Entity\User) {
            throw $this->createAccessDeniedException();
        }

        if ($user->getFavorites()->contains($cactu)) {
            $user->removeFavorite($cactu);
        } else {
            $user->addFavorite($cactu);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_cactus_show', ['id' => $cactu->getId()]);
    }

    #[Route('/{id}/edit', name: 'app_cactus_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, Cactus $cactu, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser() !== $cactu->getVendeur() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous ne pouvez modifier que vos propres annonces.');
        }

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
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Cactus $cactu, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser() !== $cactu->getVendeur() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous ne pouvez supprimer que vos propres annonces.');
        }

        if ($this->isCsrfTokenValid('delete'.$cactu->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($cactu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cactus_index', [], Response::HTTP_SEE_OTHER);
    }
}
