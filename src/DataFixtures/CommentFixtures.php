<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    private const TEXTS = [
        'Excellent vendeur, livraison rapide et cactus en parfait état !',
        'Le cactus correspond bien à la description, je recommande.',
        'Très bon contact, packaging soigné, plante superbe.',
        'Commande reçue rapidement, vendeur sérieux et professionnel.',
        'Belle plante, bien emballée. Merci !',
        'Petite déception sur la taille mais qualité irréprochable.',
        'Superbe cactus, exactement comme sur la photo. Super vendeur !',
        'Bon rapport qualité-prix, je rachèterai.',
        'Vendeur réactif et de bonne foi. Cactus en bonne santé.',
        'Très satisfait de mon achat, plante bien enracinée.',
    ];

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

            $comment = new Comment();
            $comment->setAuteur($this->getReference(UserFixtures::USER_PREFIX . $a, \App\Entity\User::class));
            $comment->setVendeur($this->getReference(UserFixtures::USER_PREFIX . $b, \App\Entity\User::class));
            $comment->setContenu($faker->randomElement(self::TEXTS));
            $comment->setDateCree($faker->dateTimeBetween('-6 months', 'now'));
            $manager->persist($comment);
        }

        $manager->flush();
    }
}
