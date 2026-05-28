<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategorieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categorie = new Categorie();
        $categorie->setName('Succulentes');
        $categorie->setDescription('Plantes grasses adaptées aux appartements et aux jardins secs.');
        $categorie->setEnvironnement('Intérieur ou extérieur lumineux');

        $manager->persist($categorie);
        $manager->flush();
    }
}
