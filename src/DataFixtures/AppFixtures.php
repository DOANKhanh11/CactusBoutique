<?php

namespace App\DataFixtures;

use App\Entity\Cactus;
use App\Entity\Categorie;
use App\Entity\Comment;
use App\Entity\Commande;
use App\Entity\Pot;
use App\Entity\Rating;
use App\Entity\Terreau;
use App\Entity\User;
use App\Entity\UserPreference;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // ── Categories ──────────────────────────────────────────
        $categoriesData = [
            ['Cactus de désert', 'Adaptés aux conditions arides, peu d\'eau nécessaire.', 'Plein soleil, extérieur'],
            ['Cactus de forêt', 'Poussent à l\'ombre sous la canopée tropicale.', 'Mi-ombre, intérieur'],
            ['Succulentes', 'Plantes grasses aux feuilles charnues stockant l\'eau.', 'Intérieur, lumière indirecte'],
            ['Cactus colonnaires', 'Variétés à croissance verticale spectaculaire.', 'Plein soleil, extérieur'],
            ['Cactus ronds', 'Formes globulaires compactes, idéales pour les débutants.', 'Intérieur, bonne luminosité'],
        ];

        $categories = [];
        foreach ($categoriesData as [$name, $desc, $env]) {
            $cat = new Categorie();
            $cat->setName($name);
            $cat->setDescription($desc);
            $cat->setEnvironnement($env);
            $manager->persist($cat);
            $categories[] = $cat;
        }

        // ── Pots ────────────────────────────────────────────────
        $potsData = [
            ['Pot Terracotta Classic', 'Terracotta', 'Terre cuite', 8.99],
            ['Pot Céramique Blanc', 'Céramique', 'Blanc', 14.50],
            ['Pot Plastique Noir', 'Plastique', 'Noir', 3.99],
            ['Pot Béton Gris', 'Béton', 'Gris', 22.00],
            ['Pot Bambou Naturel', 'Bambou', 'Naturel', 11.90],
            ['Pot Zinc Industriel', 'Zinc', 'Argent', 18.75],
        ];

        foreach ($potsData as [$name, $material, $couleur, $prix]) {
            $pot = new Pot();
            $pot->setName($name);
            $pot->setMaterial($material);
            $pot->setCouleur($couleur);
            $pot->setPrix($prix);
            $manager->persist($pot);
        }

        // ── Terreaux ────────────────────────────────────────────
        $terreauData = [
            ['Terreau Cactus Premium', 'Sable, pouzzolane, terreau universel', 6.90],
            ['Substrat Succulentes', 'Perlite, sable grossier, terre de bruyère', 8.50],
            ['Terreau Drainant Pro', 'Pouzzolane 50%, terreau universel 50%', 12.00],
            ['Mix Cactus Biologique', 'Compost certifié bio, sable, copeaux de coco', 9.90],
        ];

        foreach ($terreauData as [$name, $composition, $prix]) {
            $terreau = new Terreau();
            $terreau->setName($name);
            $terreau->setComposition($composition);
            $terreau->setPrix($prix);
            $manager->persist($terreau);
        }

        $manager->flush();

        // ── Users ────────────────────────────────────────────────
        // 1 admin
        $admin = new User();
        $admin->setNom('Admin');
        $admin->setPrenom('Super');
        $admin->setPseudo('admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);
        $this->createPreference($manager, $admin, 'light', 'fr');

        // 10 regular users
        $users = [];
        $pseudos = [];
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setNom($faker->lastName());
            $user->setPrenom($faker->firstName());

            do {
                $pseudo = $faker->unique()->userName();
                $pseudo = preg_replace('/[^a-zA-Z0-9_]/', '_', $pseudo);
                $pseudo = substr($pseudo, 0, 20);
            } while (in_array($pseudo, $pseudos));

            $pseudos[] = $pseudo;
            $user->setPseudo($pseudo);
            $user->setRoles([]);
            $user->setPassword($this->hasher->hashPassword($user, 'password'));
            $manager->persist($user);
            $this->createPreference($manager, $user, $faker->randomElement(['light', 'dark']), $faker->randomElement(['fr', 'en']));
            $users[] = $user;
        }

        $manager->flush();

        // ── Cactus listings ──────────────────────────────────────
        $noms = [
            'Echinopsis', 'Mammillaria', 'Cereus', 'Opuntia', 'Ferocactus',
            'Gymnocalycium', 'Notocactus', 'Astrophytum', 'Echinocactus', 'Parodia',
            'Cleistocactus', 'Pilosocereus', 'Melocactus', 'Turbinicarpus', 'Rebutia',
            'Aloe Vera', 'Haworthia', 'Echeveria', 'Sedum', 'Sempervivum',
        ];
        $niveaux = ['Facile', 'Moyen', 'Difficile'];
        $arrosages = [
            'Une fois par mois', 'Toutes les 2 semaines',
            'Une fois par semaine', 'Très peu, en hiver seulement',
        ];

        $cactusList = [];
        for ($i = 0; $i < 30; $i++) {
            $cactus = new Cactus();
            $cactus->setName($faker->randomElement($noms) . ' ' . $faker->bothify('##??'));
            $cactus->setDescription($faker->realText(120));
            $cactus->setPrix($faker->randomFloat(2, 5, 120));
            $cactus->setNiveauSoin($faker->randomElement($niveaux));
            $cactus->setArrosage($faker->randomElement($arrosages));
            $cactus->setTaille($faker->numberBetween(5, 80));
            $cactus->setCategorie($faker->randomElement($categories));
            $cactus->setVendeur($faker->randomElement($users));

            // 70% have an expiry date
            if ($faker->boolean(70)) {
                $cactus->setDateExpiration($faker->dateTimeBetween('+1 month', '+1 year'));
            }

            $manager->persist($cactus);
            $cactusList[] = $cactus;
        }

        $manager->flush();

        // ── Favorites ────────────────────────────────────────────
        foreach ($users as $user) {
            $favCount = $faker->numberBetween(0, 5);
            $shuffled = $faker->randomElements($cactusList, min($favCount, count($cactusList)));
            foreach ($shuffled as $cactus) {
                if ($cactus->getVendeur() !== $user) {
                    $user->addFavorite($cactus);
                }
            }
        }

        $manager->flush();

        // ── Comments ─────────────────────────────────────────────
        $commentTexts = [
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

        $commentPairs = [];
        for ($i = 0; $i < 25; $i++) {
            $auteur = $faker->randomElement($users);
            $cible  = $faker->randomElement($users);
            if ($auteur === $cible) continue;

            $key = $auteur->getPseudo() . '_' . $cible->getPseudo();
            if (in_array($key, $commentPairs)) continue;
            $commentPairs[] = $key;

            $comment = new Comment();
            $comment->setAuteur($auteur);
            $comment->setVendeur($cible);
            $comment->setContenu($faker->randomElement($commentTexts));
            $comment->setDateCree($faker->dateTimeBetween('-6 months', 'now'));
            $manager->persist($comment);
        }

        // ── Ratings ──────────────────────────────────────────────
        $ratingPairs = [];
        for ($i = 0; $i < 25; $i++) {
            $rater  = $faker->randomElement($users);
            $target = $faker->randomElement($users);
            if ($rater === $target) continue;

            $key = $rater->getPseudo() . '_' . $target->getPseudo();
            if (in_array($key, $ratingPairs)) continue;
            $ratingPairs[] = $key;

            $rating = new Rating();
            $rating->setRater($rater);
            $rating->setVendeur($target);
            $rating->setScore($faker->numberBetween(1, 5));
            $rating->setCreatedAt($faker->dateTimeBetween('-6 months', 'now'));
            $manager->persist($rating);
        }

        // ── Commandes ────────────────────────────────────────────
        $statuses = ['en attente', 'confirmée', 'expédiée', 'livrée', 'annulée'];

        for ($i = 0; $i < 15; $i++) {
            $commande = new Commande();
            $commande->setAcheteur($faker->randomElement($users));
            $commande->setDateCree($faker->dateTimeBetween('-1 year', 'now'));
            $commande->setStatus($faker->randomElement($statuses));
            $commande->setPrixTotal($faker->randomFloat(2, 10, 300));
            $commande->setAdresse($faker->streetAddress() . ', ' . $faker->postcode() . ' ' . $faker->city());
            $manager->persist($commande);
        }

        $manager->flush();
    }

    private function createPreference(ObjectManager $manager, User $user, string $theme, string $language): void
    {
        $pref = new UserPreference();
        $pref->setUser($user);
        $pref->setTheme($theme);
        $pref->setLanguage($language);
        $manager->persist($pref);
    }
}
