<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\UserPreference;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const ADMIN_REF  = 'user-admin';
    public const USER_PREFIX = 'user-';
    public const USER_COUNT  = 10;

    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Admin
        $admin = new User();
        $admin->setNom('Admin')->setPrenom('Super')->setPseudo('admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);
        $this->createPreference($manager, $admin, 'light', 'fr');
        $this->addReference(self::ADMIN_REF, $admin);

        // Regular users
        $pseudos = [];
        for ($i = 0; $i < self::USER_COUNT; $i++) {
            $user = new User();
            $user->setNom($faker->lastName())->setPrenom($faker->firstName());

            do {
                $pseudo = preg_replace('/[^a-zA-Z0-9_]/', '_', $faker->unique()->userName());
                $pseudo = substr($pseudo, 0, 20);
            } while (in_array($pseudo, $pseudos));

            $pseudos[] = $pseudo;
            $user->setPseudo($pseudo)->setRoles([]);
            $user->setPassword($this->hasher->hashPassword($user, 'password'));
            $manager->persist($user);
            $this->createPreference(
                $manager, $user,
                $faker->randomElement(['light', 'dark']),
                $faker->randomElement(['fr', 'en'])
            );
            $this->addReference(self::USER_PREFIX . $i, $user);
        }

        $manager->flush();
    }

    private function createPreference(ObjectManager $manager, User $user, string $theme, string $language): void
    {
        $pref = new UserPreference();
        $pref->setUser($user)->setTheme($theme)->setLanguage($language);
        $manager->persist($pref);
    }
}
