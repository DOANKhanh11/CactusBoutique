<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategorieFixtures extends Fixture
{
    public const PREFIX = 'categorie-';

    private const DATA = [
        ['Cactus de désert',    'Adaptés aux conditions arides, peu d\'eau nécessaire.',      'Plein soleil, extérieur'],
        ['Cactus de forêt',     'Poussent à l\'ombre sous la canopée tropicale.',             'Mi-ombre, intérieur'],
        ['Succulentes',         'Plantes grasses aux feuilles charnues stockant l\'eau.',     'Intérieur, lumière indirecte'],
        ['Cactus colonnaires',  'Variétés à croissance verticale spectaculaire.',             'Plein soleil, extérieur'],
        ['Cactus ronds',        'Formes globulaires compactes, idéales pour les débutants.', 'Intérieur, bonne luminosité'],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::DATA as $i => [$name, $desc, $env]) {
            $cat = new Categorie();
            $cat->setName($name)->setDescription($desc)->setEnvironnement($env);
            $manager->persist($cat);
            $this->addReference(self::PREFIX . $i, $cat);
        }
        $manager->flush();
    }
}
