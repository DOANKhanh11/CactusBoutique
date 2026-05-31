<?php

namespace App\Controller;

use App\Entity\Cactus;
use App\Entity\Commande;
use App\Entity\ContenuCommande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/commande')]
#[IsGranted('ROLE_USER')]
class CommandeController extends AbstractController
{
    #[Route('/acheter/{id}', name: 'app_commande_buy', methods: ['GET', 'POST'])]
    public function buy(Cactus $cactus, Request $request, EntityManagerInterface $em): Response
    {
        if ($cactus->getVendeur() === $this->getUser()) {
            $this->addFlash('error', 'commande.self_buy_error');
            return $this->redirectToRoute('app_cactus_show', ['id' => $cactus->getId()]);
        }

        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ligne = new ContenuCommande();
            $ligne->setCactus($cactus);
            $ligne->setQuantite(1);
            $ligne->setPrixUnitaire($cactus->getPrix());
            $ligne->setCommande($commande);

            $commande->setAcheteur($this->getUser());
            $commande->setDateCree(new \DateTime());
            $commande->setStatus('en attente');
            $commande->setPrixTotal($cactus->getPrix());

            $em->persist($commande);
            $em->persist($ligne);
            $em->flush();

            $this->addFlash('success', 'commande.success');
            return $this->redirectToRoute('app_commande_show', ['id' => $commande->getId()]);
        }

        return $this->render('commande/buy.html.twig', [
            'cactus' => $cactus,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        if ($commande->getAcheteur() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('', name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findBy(
                ['acheteur' => $this->getUser()],
                ['dateCree' => 'DESC']
            ),
        ]);
    }
}
