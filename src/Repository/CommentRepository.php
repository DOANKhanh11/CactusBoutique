<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function findBySeller(User $seller): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.auteur', 'a')
            ->addSelect('a')
            ->where('c.cible = :seller')
            ->setParameter('seller', $seller)
            ->orderBy('c.dateCree', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
