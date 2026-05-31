<?php

namespace App\DataFixtures;

use App\Entity\Rating;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class RatingFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }

    public function load(ObjectManager $manager): void
    {
        $faker  = Factory::create('fr_FR');
        $pairs  = [];
        $target = 25;
        $tries  = 0;

        while (count($pairs) < $target && $tries < 200) {
            $tries++;
            $a = $faker->numberBetween(0, UserFixtures::USER_COUNT - 1);
            $b = $faker->numberBetween(0, UserFixtures::USER_COUNT - 1);
            if ($a === $b) continue;

            $key = "$a-$b";
            if (isset($pairs[$key])) continue;
            $pairs[$key] = true;

            $rating = new Rating();
            $rating->setRater($this->getReference(UserFixtures::USER_PREFIX . $a, \App\Entity\User::class));
            $rating->setVendeur($this->getReference(UserFixtures::USER_PREFIX . $b, \App\Entity\User::class));
            $rating->setScore($faker->numberBetween(1, 5));
            $rating->setCreatedAt($faker->dateTimeBetween('-6 months', 'now'));
            $manager->persist($rating);
        }

        $manager->flush();
    }
}
