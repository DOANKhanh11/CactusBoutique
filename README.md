# 🌵 Cactus Boutique

Boutique en ligne de cactus, plantes et accessoires de jardinage.
Application web MVC développée avec **Symfony 6.4** et **PHP 8.2**.

## Fonctionnalités

- 🔐 Inscription / connexion / déconnexion
- 🌵 CRUD complet : cactus, pots, catégories, terreaux
- 🛒 Achat d'articles et historique des commandes
- ❤️ Favoris et espace personnel
- 💬 Commentaires et ⭐ notation des vendeurs
- 🔍 Recherche avancée multi-critères (nom, catégorie, prix, soin, expiration)
- 👮 Gestion des rôles (administrateur / membre)
- 🎨 Thème clair / sombre (mémorisé en base)
- 🌍 Multilingue français / anglais

## Installation rapide

```bash
composer install
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction
symfony server:start
```

➡️ **Guide complet d'installation** : voir [GUIDE_INSTALLATION.md](GUIDE_INSTALLATION.md)
➡️ **Guide de test des fonctionnalités** : voir [GUIDE_TEST.md](GUIDE_TEST.md)
➡️ **Guide de déploiement (Alwaysdata)** : voir [GUIDE_DEPLOIEMENT.md](GUIDE_DEPLOIEMENT.md)

## 🔗 Application en ligne

`https://<votre-compte>.alwaysdata.net` *(à compléter après déploiement)*

## Comptes de démonstration

| Rôle | Pseudo | Mot de passe |
|------|--------|--------------|
| Administrateur | `admin` | `admin123` |
| Membre | *(voir `SELECT pseudo FROM user`)* | `password` |

## Stack technique

Symfony 6.4 · PHP 8.2 · Doctrine ORM · Twig · MySQL · Faker (fixtures)
