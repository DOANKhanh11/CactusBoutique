<?php

namespace App\Repository;

use App\Entity\Rating;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rating>
 */
class RatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rating::class);
    }

    public function findBySeller(User $seller): array
    {
        return $this->findBy(['target' => $seller], ['createdAt' => 'DESC']);
    }

    public function findOneByRaterAndSeller(User $rater, User $seller): ?Rating
    {
        return $this->findOneBy(['rater' => $rater, 'target' => $seller]);
    }

    public function getAverageScore(User $seller): float
    {
        $result = $this->createQueryBuilder('r')
            ->select('AVG(r.score) as avg')
            ->where('r.target = :seller')
            ->setParameter('seller', $seller)
            ->getQuery()
            ->getSingleScalarResult();

        return round((float) $result, 1);
    }
}
