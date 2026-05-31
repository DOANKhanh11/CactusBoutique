# 🌵 Cactus Boutique — Guide d'installation et d'utilisation

Application web de vente de cactus, plantes et accessoires de jardinage, développée
avec **Symfony 6.4** et **PHP 8.2**.

Ce guide décrit l'installation complète sur une machine **ne disposant ni de PHP, ni
de Composer, ni de Symfony**.

---

## 1. Prérequis à installer

### 1.1 PHP 8.2+

| Système | Commande / méthode |
|---------|--------------------|
| **macOS** | Installer [MAMP](https://www.mamp.info/) (fournit PHP + MySQL sur le port 8889), ou `brew install php` |
| **Windows** | Installer [XAMPP](https://www.apachefriends.org/) ou [WAMP](https://www.wampserver.com/) |
| **Linux (Ubuntu/Debian)** | `sudo apt install php8.2 php8.2-cli php8.2-mysql php8.2-xml php8.2-mbstring php8.2-intl php8.2-curl php8.2-zip` |

Extensions PHP requises : `ctype`, `iconv`, `intl`, `mbstring`, `pdo_mysql`, `xml`.

Vérifier l'installation :
```bash
php -v        # doit afficher PHP 8.2 ou supérieur
```

### 1.2 Composer (gestionnaire de dépendances PHP)

Téléchargez et installez depuis **https://getcomposer.org/download/**.

Vérifier :
```bash
composer --version
```

### 1.3 Symfony CLI (optionnel mais recommandé)

Permet de lancer le serveur web local facilement.
Téléchargez depuis **https://symfony.com/download**.

```bash
symfony version
```

### 1.4 MySQL / MariaDB

Fourni par MAMP/XAMPP/WAMP, ou installé séparément.

---

## 2. Configuration de la base de données

L'application est configurée pour se connecter avec les paramètres **imposés** suivants :

| Paramètre | Valeur |
|-----------|--------|
| host | `localhost` |
| port | `8889` |
| dbname | `db_devWeb` |
| user | `user_devWeb` |
| mot de passe | `mdp_devWeb` |
| charset | `utf8` |

### 2.1 Créer l'utilisateur et la base

Connectez-vous à MySQL (avec l'utilisateur root) :
```bash
mysql -u root -p -h localhost -P 8889
```

Puis exécutez :
```sql
CREATE DATABASE db_devWeb CHARACTER SET utf8 COLLATE utf8_general_ci;
CREATE USER 'user_devWeb'@'localhost' IDENTIFIED BY 'mdp_devWeb';
GRANT ALL PRIVILEGES ON db_devWeb.* TO 'user_devWeb'@'localhost';
FLUSH PRIVILEGES;
```

### 2.2 Vérifier le fichier `.env`

À la racine du projet, le fichier `.env` (ou `.env.local`) doit contenir :
```dotenv
DATABASE_URL="mysql://user_devWeb:mdp_devWeb@localhost:8889/db_devWeb?serverVersion=8.0&charset=utf8"
```

> 💡 Bonne pratique : copiez `.env` vers `.env.local` et n'éditez que `.env.local`
> (qui n'est pas versionné).

---

## 3. Installation de l'application

Depuis le dossier du projet (là où se trouve `composer.json`) :

```bash
# 1. Installer toutes les dépendances PHP (crée le dossier vendor/)
composer install

# 2. Créer le schéma de la base via les migrations
php bin/console doctrine:migrations:migrate --no-interaction

# 3. (Optionnel) Charger les données de démonstration
php bin/console doctrine:fixtures:load --no-interaction
```

> Le dossier `vendor/` n'est **pas** fourni dans le livrable : `composer install`
> le régénère intégralement à partir de `composer.json` et `composer.lock`.

---

## 4. Lancer l'application

### Option A — Serveur Symfony (recommandé)
```bash
symfony server:start
```
Puis ouvrez **https://127.0.0.1:8000**.

### Option B — Serveur PHP intégré
```bash
php -S localhost:8000 -t public/
```
Puis ouvrez **http://localhost:8000**.

---

## 5. Comptes de démonstration

Après chargement des fixtures :

| Rôle | Pseudo | Mot de passe |
|------|--------|--------------|
| **Administrateur** | `admin` | `admin123` |
| **Membre** | *(voir la liste des utilisateurs)* | `password` |

Pour lister les pseudos générés :
```bash
php bin/console dbal:run-sql "SELECT pseudo FROM user"
```

---

## 6. Fonctionnalités

- 🔐 **Inscription / Connexion / Déconnexion**
- 🌵 **CRUD** complet : cactus, pots, catégories, terreaux
- 🛒 **Achat** d'un article et historique des **commandes**
- ❤️ **Favoris** et **espace personnel** (profil)
- 💬 **Commentaires** et ⭐ **notation** des vendeurs
- 🔍 **Recherche avancée** (nom, catégorie, prix, niveau de soin, date d'expiration)
- 👮 **Gestion des rôles** (administrateur / membre) avec back-office
- 🎨 **Thème clair / sombre** (mémorisé en base)
- 🌍 **Multilingue** français / anglais

---

## 7. Bundles utilisés et justification

| Bundle | Rôle | Pourquoi ce choix |
|--------|------|-------------------|
| **symfony/framework-bundle** | Cœur du framework (routing, contrôleurs, conteneur de services) | Base indispensable de toute application Symfony |
| **doctrine/orm** + **doctrine/doctrine-bundle** | Mapping objet-relationnel (entités ↔ tables) | Standard de fait pour la persistance en Symfony ; évite d'écrire du SQL manuel |
| **doctrine/doctrine-migrations-bundle** | Versionnement du schéma de base | Permet de recréer/mettre à jour la BDD de façon reproductible sur n'importe quelle machine |
| **symfony/security-bundle** | Authentification, hachage des mots de passe, contrôle d'accès par rôle | Gère login/logout et la protection des routes selon `ROLE_USER` / `ROLE_ADMIN` |
| **symfony/form** + **symfony/validator** | Création et validation des formulaires | Génère les formulaires CRUD, recherche, commande ; valide les données saisies |
| **symfony/twig-bundle** | Moteur de templates | Sépare la logique de la présentation, héritage de gabarits (`base.html.twig`) |
| **symfony/translation** | Internationalisation (i18n) | Implémente le multilingue FR/EN exigé par le cahier des charges |
| **symfony/intl** | Données de localisation | Support des langues/locales pour la traduction |
| **doctrine/doctrine-fixtures-bundle** | Jeux de données de test | Permet de remplir la base avec des données cohérentes pour la démonstration |
| **fakerphp/faker** | Génération de fausses données réalistes | Crée utilisateurs, annonces, commentaires, notes variés sans saisie manuelle |
| **symfony/maker-bundle** *(dev)* | Génération de code (entités, contrôleurs, CRUD) | Accélère le développement en générant le code répétitif |
| **symfony/console** | Commandes en ligne (`bin/console`) | Lance migrations, fixtures, vidage du cache, etc. |

---

## 8. Commandes utiles

```bash
# Vider le cache
php bin/console cache:clear

# Lister toutes les routes
php bin/console debug:router

# Vérifier la synchronisation BDD / entités
php bin/console doctrine:schema:validate

# Recharger les données de démonstration
php bin/console doctrine:fixtures:load --no-interaction
```

---

## 9. Dépannage

| Problème | Solution |
|----------|----------|
| `SQLSTATE[HY000] [2002] Connection refused` | Vérifier que MySQL tourne sur le port **8889** et que `.env` est correct |
| `Class not found` après `git pull` | Lancer `composer install` puis `php bin/console cache:clear` |
| Page blanche / erreur 500 | Passer en mode dev : `APP_ENV=dev` dans `.env`, consulter `var/log/dev.log` |
| Les traductions ne changent pas | Vider le cache : `php bin/console cache:clear` |

---

*Projet réalisé dans le cadre du module Développement Web — Symfony 6.4 / PHP 8.2.*
