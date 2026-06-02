<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRoleType;
use App\Repository\CactusRepository;
use App\Repository\PotRepository;
use App\Repository\TerreauRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('', name: 'admin_dashboard')]
    public function dashboard(UserRepository $userRepository): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'users' => $userRepository->findAll(),
            'total_users' => count($userRepository->findAll()),
        ]);
    }

    #[Route('/users', name: 'admin_users')]
    public function users(UserRepository $userRepository): Response
    {
        return $this->render('admin/users/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/data', name: 'admin_data')]
    public function data(
        CactusRepository $cactusRepository,
        TerreauRepository $terreauRepository,
        PotRepository $potRepository
    ): Response {
        $cacti = $cactusRepository->findAll();
        $terreaux = $terreauRepository->findAll();
        $pots = $potRepository->findAll();

        $totalCactus = array_sum(array_map(fn($c) => $c->getPrix(), $cacti));
        $totalTerreau = array_sum(array_map(fn($t) => $t->getPrix(), $terreaux));
        $totalPot = array_sum(array_map(fn($p) => $p->getPrix(), $pots));

        return $this->render('admin/data/index.html.twig', [
            'cacti' => $cacti,
            'terreaux' => $terreaux,
            'pots' => $pots,
            'totalCactus' => $totalCactus,
            'totalTerreau' => $totalTerreau,
            'totalPot' => $totalPot,
            'grandTotal' => $totalCactus + $totalTerreau + $totalPot,
        ]);
    }

    #[Route('/users/{id}/role', name: 'admin_user_role', methods: ['GET', 'POST'])]
    public function editRole(User $user, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(UserRoleType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'admin.roles_updated');
            return $this->redirectToRoute('admin_users');
        }

        return $this->render('admin/users/edit_role.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/users/{id}/delete', name: 'admin_user_delete', methods: ['POST'])]
    public function deleteUser(User $user, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete_user_' . $user->getId(), $request->request->get('_token'))) {
            $em->remove($user);
            $em->flush();
            $this->addFlash('success', 'admin.user_deleted');
        }

        return $this->redirectToRoute('admin_users');
    }
}
