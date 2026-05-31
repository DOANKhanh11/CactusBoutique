<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class FavoriteFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [UserFixtures::class, CactusFixtures::class];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < UserFixtures::USER_COUNT; $i++) {
            /** @var \App\Entity\User $user */
            $user = $this->getReference(UserFixtures::USER_PREFIX . $i, \App\Entity\User::class);

            $count = $faker->numberBetween(0, 5);
            $indices = $faker->randomElements(range(0, CactusFixtures::COUNT - 1), $count);

            foreach ($indices as $j) {
                /** @var \App\Entity\Cactus $cactus */
                $cactus = $this->getReference(CactusFixtures::PREFIX . $j, \App\Entity\Cactus::class);
                if ($cactus->getVendeur() !== $user) {
                    $user->addFavorite($cactus);
                }
            }
        }

        $manager->flush();
    }
}
