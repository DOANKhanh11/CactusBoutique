<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/profile')]
final class ProfileController extends AbstractController
{
    #[Route('', name: 'app_profile', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à votre profil.');
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'favorites' => $user->getFavorites(),
            'orders' => $user->getCommande(),
            'annonces' => $user->getCactusVendus(),
        ]);
    }

    #[Route('/delete', name: 'app_profile_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        if (!$this->isCsrfTokenValid('delete_account', $request->request->get('_token'))) {
            $this->addFlash('error', 'Token invalide.');
            return $this->redirectToRoute('app_profile');
        }

        // Detach cactus listings from this vendor before removing
        foreach ($user->getCactusVendus() as $cactus) {
            $cactus->setVendeur(null);
        }

        $this->container->get('security.token_storage')->setToken(null);
        $request->getSession()->invalidate();

        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('app_home');
    }
}
