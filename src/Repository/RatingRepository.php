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

    public function findByVendeur(User $vendeur): array
    {
        return $this->findBy(['vendeur' => $vendeur], ['createdAt' => 'DESC']);
    }

    public function findOneByRaterAndVendeur(User $rater, User $vendeur): ?Rating
    {
        return $this->findOneBy(['rater' => $rater, 'vendeur' => $vendeur]);
    }

    public function getAverageScore(User $vendeur): float
    {
        $result = $this->createQueryBuilder('r')
            ->select('AVG(r.score) as avg')
            ->where('r.vendeur = :vendeur')
            ->setParameter('vendeur', $vendeur)
            ->getQuery()
            ->getSingleScalarResult();

        return round((float) $result, 1);
    }
}
