# 🚀 Guide de déploiement — Alwaysdata

Déploiement de **Cactus Boutique** (Symfony 6.4 + MySQL) sur l'hébergeur gratuit
**Alwaysdata**.

> 🔗 **Lien de l'application en ligne :** `https://<votre-compte>.alwaysdata.net`
> *(à remplacer par votre URL réelle une fois le déploiement terminé)*

---

## Pourquoi Alwaysdata ?

- Offre **gratuite** (100 Mo) avec **PHP 8.2** et **MySQL** inclus
- Pas de carte bancaire requise
- Accès **SSH** + **Git** + **SFTP**
- Très utilisé pour les projets étudiants Symfony en France

---

## Étape 1 — Créer un compte

1. Aller sur **https://www.alwaysdata.com/fr/** → **Inscription**
2. Choisir l'offre **« Public » gratuite (100 Mo)**
3. Choisir un nom de compte, ex. `cactusboutique`
   → votre domaine sera `cactusboutique.alwaysdata.net`
4. Valider l'email d'activation

---

## Étape 2 — Créer la base de données MySQL

Dans le panneau d'administration (**admin.alwaysdata.com**) :

1. Menu **Bases de données → MySQL → Ajouter une base de données**
2. Renseigner :
   - **Nom** : `cactusboutique_db`
   - Cocher **Créer un utilisateur** : `cactusboutique` avec un mot de passe (notez-le)
3. Valider

Vous obtenez :
- Hôte : `mysql-cactusboutique.alwaysdata.net`
- Base : `cactusboutique_db`
- Utilisateur : `cactusboutique`

---

## Étape 3 — Configurer le site web

Menu **Web → Sites → Ajouter un site** :

| Champ | Valeur |
|-------|--------|
| **Adresses** | `cactusboutique.alwaysdata.net` |
| **Type** | PHP |
| **Version PHP** | 8.2 |
| **Répertoire racine** | `/www/cactusboutique/public` |
| **Commande** | *(laisser vide)* |

> ⚠️ Le **répertoire racine doit pointer sur `public/`**, pas sur la racine du projet.

---

## Étape 4 — Envoyer le code

### Option A — Via Git (recommandé)

Connectez-vous en SSH (identifiants dans **SSH** du panneau) :
```bash
ssh cactusboutique@ssh-cactusboutique.alwaysdata.net
cd ~/www
git clone <URL_DE_VOTRE_DEPOT> cactusboutique
cd cactusboutique
```

### Option B — Via SFTP

Téléversez tout le projet **sauf `vendor/` et `var/`** dans `~/www/cactusboutique`
avec un client SFTP (FileZilla, Cyberduck) :
- Hôte : `ssh-cactusboutique.alwaysdata.net`
- Identifiants SSH du panneau

---

## Étape 5 — Configurer l'environnement de production

Sur le serveur, dans le dossier du projet, créer le fichier **`.env.local`** :
```bash
nano .env.local
```

Contenu :
```dotenv
APP_ENV=prod
APP_DEBUG=0
APP_SECRET=3ff15a94905b311c3ac19f385a3ede29
DATABASE_URL="mysql://cactusboutique:VOTRE_MOT_DE_PASSE@mysql-cactusboutique.alwaysdata.net:3306/cactusboutique_db?serverVersion=8.0&charset=utf8mb4"
MAILER_DSN=null://null
MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0
```

> Remplacez `VOTRE_MOT_DE_PASSE` par celui défini à l'étape 2.

---

## Étape 6 — Installer et initialiser

Toujours en SSH, dans le dossier du projet :

```bash
# 1. Composer (Alwaysdata le fournit déjà)
composer install --no-dev --optimize-autoloader

# 2. Créer le schéma de la base directement depuis les entités
php bin/console doctrine:schema:create

# 3. Charger les données de démonstration
#    (les fixtures sont en require-dev : on les autorise ponctuellement)
composer require --dev doctrine/doctrine-fixtures-bundle fakerphp/faker
php bin/console doctrine:fixtures:load --no-interaction

# 4. Vider et préchauffer le cache de production
php bin/console cache:clear --env=prod
php bin/console cache:warmup --env=prod
```

> 💡 Si `doctrine:schema:create` indique que des tables existent déjà, lancez
> d'abord `php bin/console doctrine:schema:drop --force` puis recréez.

---

## Étape 7 — Vérifier

Ouvrez **https://cactusboutique.alwaysdata.net**

- La page d'accueil s'affiche ✅
- Connexion possible avec `admin` / `admin123` ✅

---

## Étape 8 — Mettre à jour le lien dans les guides

Une fois en ligne, renseignez l'URL réelle en haut de ce fichier **et** dans le
[README.md](README.md).

---

## Dépannage

| Problème | Solution |
|----------|----------|
| Erreur 500 | Vérifier `APP_ENV=prod`, le bon `DATABASE_URL`, puis `cache:clear --env=prod` |
| « No such file or directory » MySQL | Vérifier l'hôte `mysql-<compte>.alwaysdata.net` et le port `3306` |
| Page blanche / assets manquants | Vérifier que le **répertoire racine = `public/`** |
| Permission denied sur `var/` | `chmod -R 777 var/` |
| Fixtures échouent | Lancer `doctrine:schema:drop --force` puis `doctrine:schema:create` avant |

---

## Alternative : déploiement sans hébergeur (PDF)

En cas d'impossibilité de déployer, le cahier des charges autorise un **document PDF
avec captures d'écran de toutes les fonctionnalités**. Suivez le
[GUIDE_TEST.md](GUIDE_TEST.md) et capturez chaque étape.

---

*Hébergement : Alwaysdata (offre gratuite) — Symfony 6.4 / PHP 8.2 / MySQL.*
