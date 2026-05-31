<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Rating;
use App\Entity\User;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\RatingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/seller')]
class SellerController extends AbstractController
{
    #[Route('/{id}', name: 'app_seller_show', methods: ['GET', 'POST'])]
    public function show(
        User $seller,
        Request $request,
        CommentRepository $commentRepo,
        RatingRepository $ratingRepo,
        EntityManagerInterface $em
    ): Response {
        $commentForm = null;
        $currentUserRating = null;

        if ($this->isGranted('ROLE_USER') && $this->getUser() !== $seller) {
            $comment = new Comment();
            $commentForm = $this->createForm(CommentType::class, $comment);
            $commentForm->handleRequest($request);

            if ($commentForm->isSubmitted() && $commentForm->isValid()) {
                $comment->setAuteur($this->getUser());
                $comment->setVendeur($seller);
                $comment->setDateCree(new \DateTime());
                $em->persist($comment);
                $em->flush();
                $this->addFlash('success', 'flash.comment_published');
                return $this->redirectToRoute('app_seller_show', ['id' => $seller->getId()]);
            }

            $currentUserRating = $ratingRepo->findOneByRaterAndSeller($this->getUser(), $seller);
        }

        if ($request->isMethod('POST') && $request->request->has('score')) {
            $this->denyAccessUnlessGranted('ROLE_USER');
            if ($this->getUser() === $seller) {
                $this->addFlash('error', 'seller.self_rating_error');
                return $this->redirectToRoute('app_seller_show', ['id' => $seller->getId()]);
            }
            $score = (int) $request->request->get('score');
            if ($score >= 1 && $score <= 5) {
                $rating = $ratingRepo->findOneByRaterAndSeller($this->getUser(), $seller) ?? new Rating();
                $rating->setRater($this->getUser());
                $rating->setVendeur($seller);
                $rating->setScore($score);
                if (!$rating->getCreatedAt()) {
                    $rating->setCreatedAt(new \DateTime());
                }
                $em->persist($rating);
                $em->flush();
                $this->addFlash('success', 'flash.rating_saved');
            }
            return $this->redirectToRoute('app_seller_show', ['id' => $seller->getId()]);
        }

        return $this->render('seller/show.html.twig', [
            'seller' => $seller,
            'comments' => $commentRepo->findBySeller($seller),
            'avgRating' => $ratingRepo->getAverageScore($seller),
            'ratingCount' => count($ratingRepo->findBySeller($seller)),
            'currentUserRating' => $currentUserRating,
            'commentForm' => $commentForm?->createView(),
        ]);
    }

    #[Route('/{id}/comment/{commentId}/delete', name: 'app_seller_comment_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function deleteComment(
        User $seller,
        int $commentId,
        Request $request,
        CommentRepository $commentRepo,
        EntityManagerInterface $em
    ): Response {
        $comment = $commentRepo->find($commentId);
        if (!$comment) {
            throw $this->createNotFoundException();
        }
        if ($comment->getAuteur() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }
        if ($this->isCsrfTokenValid('delete_comment_' . $commentId, $request->request->get('_token'))) {
            $em->remove($comment);
            $em->flush();
            $this->addFlash('success', 'flash.comment_deleted');
        }
        return $this->redirectToRoute('app_seller_show', ['id' => $seller->getId()]);
    }
}
