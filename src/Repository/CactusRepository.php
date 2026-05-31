<?php

namespace App\Repository;

use App\Entity\Cactus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cactus>
 */
class CactusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cactus::class);
    }

    /**
     * @return Cactus[]
     */
    public function search(array $criteria): array
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.categorie', 'cat')
            ->leftJoin('c.vendeur', 'v')
            ->addSelect('cat', 'v');

        if (!empty($criteria['nom'])) {
            $qb->andWhere('c.name LIKE :nom')
               ->setParameter('nom', '%' . $criteria['nom'] . '%');
        }

        if (!empty($criteria['categorie'])) {
            $qb->andWhere('c.categorie = :categorie')
               ->setParameter('categorie', $criteria['categorie']);
        }

        if (!empty($criteria['niveauSoin'])) {
            $qb->andWhere('c.niveauSoin = :niveauSoin')
               ->setParameter('niveauSoin', $criteria['niveauSoin']);
        }

        if (!empty($criteria['prixMin'])) {
            $qb->andWhere('c.prix >= :prixMin')
               ->setParameter('prixMin', $criteria['prixMin']);
        }

        if (!empty($criteria['prixMax'])) {
            $qb->andWhere('c.prix <= :prixMax')
               ->setParameter('prixMax', $criteria['prixMax']);
        }

        if (!empty($criteria['expirationAvant'])) {
            $qb->andWhere('c.dateExpiration <= :expAvant OR c.dateExpiration IS NULL')
               ->setParameter('expAvant', $criteria['expirationAvant']);
        }

        match ($criteria['tri'] ?? null) {
            'prix_asc'       => $qb->orderBy('c.prix', 'ASC'),
            'prix_desc'      => $qb->orderBy('c.prix', 'DESC'),
            'date_desc'      => $qb->orderBy('c.id', 'DESC'),
            'expiration_asc' => $qb->orderBy('c.dateExpiration', 'ASC'),
            default          => $qb->orderBy('c.id', 'DESC'),
        };

        return $qb->getQuery()->getResult();
    }
}
