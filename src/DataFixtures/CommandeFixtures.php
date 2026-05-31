<?php

namespace App\DataFixtures;

use App\Entity\Commande;
use App\Entity\ContenuCommande;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommandeFixtures extends Fixture implements DependentFixtureInterface
{
    private const STATUSES = ['en attente', 'confirmée', 'expédiée', 'livrée', 'annulée'];

    public function getDependencies(): array
    {
        return [UserFixtures::class, CactusFixtures::class];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 15; $i++) {
            $buyerIndex  = $faker->numberBetween(0, UserFixtures::USER_COUNT - 1);
            $cactusIndex = $faker->numberBetween(0, CactusFixtures::COUNT - 1);

            /** @var \App\Entity\User $buyer */
            $buyer = $this->getReference(UserFixtures::USER_PREFIX . $buyerIndex, \App\Entity\User::class);

            /** @var \App\Entity\Cactus $cactus */
            $cactus = $this->getReference(CactusFixtures::PREFIX . $cactusIndex, \App\Entity\Cactus::class);

            // Skip if buyer is the seller
            if ($cactus->getVendeur() === $buyer) continue;

            $commande = new Commande();
            $commande->setAcheteur($buyer);
            $commande->setDateCree($faker->dateTimeBetween('-1 year', 'now'));
            $commande->setStatus($faker->randomElement(self::STATUSES));
            $commande->setPrixTotal($cactus->getPrix());
            $commande->setAdresse($faker->streetAddress() . ', ' . $faker->postcode() . ' ' . $faker->city());

            $ligne = new ContenuCommande();
            $ligne->setCactus($cactus);
            $ligne->setQuantite(1);
            $ligne->setPrixUnitaire($cactus->getPrix());
            $ligne->setCommande($commande);

            $manager->persist($commande);
            $manager->persist($ligne);
        }

        $manager->flush();
    }
}
