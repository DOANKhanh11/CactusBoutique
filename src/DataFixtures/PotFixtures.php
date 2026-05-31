<?php

namespace App\DataFixtures;

use App\Entity\Pot;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PotFixtures extends Fixture
{
    private const DATA = [
        ['Pot Terracotta Classic', 'Terracotta', 'Terre cuite', 8.99],
        ['Pot Céramique Blanc',    'Céramique',  'Blanc',       14.50],
        ['Pot Plastique Noir',     'Plastique',  'Noir',        3.99],
        ['Pot Béton Gris',         'Béton',      'Gris',        22.00],
        ['Pot Bambou Naturel',     'Bambou',     'Naturel',     11.90],
        ['Pot Zinc Industriel',    'Zinc',       'Argent',      18.75],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::DATA as [$name, $material, $couleur, $prix]) {
            $pot = new Pot();
            $pot->setName($name)->setMaterial($material)->setCouleur($couleur)->setPrix($prix);
            $manager->persist($pot);
        }
        $manager->flush();
    }
}
