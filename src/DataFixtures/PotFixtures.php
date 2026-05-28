<?php

namespace App\DataFixtures;

use App\Entity\Pot;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PotFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $pot = new Pot();
        $pot->setName('Pot en terre cuite');
        $pot->setMaterial('Terre cuite');
        $pot->setCouleur('Terracotta');
        $pot->setPrix(14.50);

        $manager->persist($pot);
        $manager->flush();
    }
}
