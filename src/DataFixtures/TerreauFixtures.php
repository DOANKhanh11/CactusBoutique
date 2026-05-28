<?php

namespace App\DataFixtures;

use App\Entity\Terreau;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TerreauFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $terreau = new Terreau();
        $terreau->setName('Terreau spécial cactus');
        $terreau->setComposition('Tourbe, sable, perlite');
        $terreau->setPrix(7.90);

        $manager->persist($terreau);
        $manager->flush();
    }
}
