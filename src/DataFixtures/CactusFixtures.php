<?php

namespace App\DataFixtures;

use App\Entity\Cactus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CactusFixtures extends Fixture implements DependentFixtureInterface
{
    public const PREFIX = 'cactus-';
    public const COUNT  = 30;

    private const NOMS = [
        'Echinopsis', 'Mammillaria', 'Cereus', 'Opuntia', 'Ferocactus',
        'Gymnocalycium', 'Notocactus', 'Astrophytum', 'Echinocactus', 'Parodia',
        'Cleistocactus', 'Pilosocereus', 'Melocactus', 'Turbinicarpus', 'Rebutia',
        'Aloe Vera', 'Haworthia', 'Echeveria', 'Sedum', 'Sempervivum',
    ];

    private const ARROSAGES = [
        'Une fois par mois',
        'Toutes les 2 semaines',
        'Une fois par semaine',
        'Très peu, en hiver seulement',
    ];

    public function getDependencies(): array
    {
        return [UserFixtures::class, CategorieFixtures::class];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < self::COUNT; $i++) {
            /** @var \App\Entity\User $vendeur */
            $vendeur = $this->getReference(UserFixtures::USER_PREFIX . ($i % UserFixtures::USER_COUNT), \App\Entity\User::class);

            /** @var \App\Entity\Categorie $categorie */
            $categorie = $this->getReference(CategorieFixtures::PREFIX . $faker->numberBetween(0, 4), \App\Entity\Categorie::class);

            $cactus = new Cactus();
            $cactus->setName($faker->randomElement(self::NOMS) . ' ' . $faker->bothify('##??'));
            $cactus->setDescription($faker->realText(120));
            $cactus->setPrix($faker->randomFloat(2, 5, 120));
            $cactus->setNiveauSoin($faker->randomElement(['Facile', 'Moyen', 'Difficile']));
            $cactus->setArrosage($faker->randomElement(self::ARROSAGES));
            $cactus->setTaille($faker->numberBetween(5, 80));
            $cactus->setCategorie($categorie);
            $cactus->setVendeur($vendeur);

            if ($faker->boolean(70)) {
                $cactus->setDateExpiration($faker->dateTimeBetween('+1 month', '+1 year'));
            }

            $manager->persist($cactus);
            $this->addReference(self::PREFIX . $i, $cactus);
        }

        $manager->flush();
    }
}
