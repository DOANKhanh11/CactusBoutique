<?php

namespace App\DataFixtures;

use App\Entity\Cactus;
use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CactusFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categorie = new Categorie();
        $categorie->setName('Succulente');
        $categorie->setDescription('Plantes grasses faciles à entretenir pour l’intérieur.');
        $categorie->setEnvironnement('Intérieur, lumière indirecte');
        $manager->persist($categorie);

        $cactus = new Cactus();
        $cactus->setName('Echinopsis');
        $cactus->setDescription('Petit cactus rond aux fleurs colorées.');
        $cactus->setPrix(19.99);
        $cactus->setNiveauSoin('Facile');
        $cactus->setArrosage('Une fois toutes les deux semaines');
        $cactus->setTaille(12);
        $cactus->setCategorie($categorie);
        $manager->persist($cactus);

        $manager->flush();
    }
}
