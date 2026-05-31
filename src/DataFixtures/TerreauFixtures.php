<?php

namespace App\DataFixtures;

use App\Entity\Terreau;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TerreauFixtures extends Fixture
{
    private const DATA = [
        ['Terreau Cactus Premium',  'Sable, pouzzolane, terreau universel',              6.90],
        ['Substrat Succulentes',    'Perlite, sable grossier, terre de bruyère',         8.50],
        ['Terreau Drainant Pro',    'Pouzzolane 50%, terreau universel 50%',             12.00],
        ['Mix Cactus Biologique',   'Compost certifié bio, sable, copeaux de coco',      9.90],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::DATA as [$name, $composition, $prix]) {
            $terreau = new Terreau();
            $terreau->setName($name)->setComposition($composition)->setPrix($prix);
            $manager->persist($terreau);
        }
        $manager->flush();
    }
}
