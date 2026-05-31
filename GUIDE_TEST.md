# 🧪 Guide de test — Cactus Boutique

Scénario de test manuel couvrant **toutes les fonctionnalités**.
Suivez les étapes dans l'ordre pour valider l'application.

## Préparation

```bash
# 1. Charger des données fraîches
php bin/console doctrine:fixtures:load --no-interaction

# 2. Démarrer le serveur
symfony server:start
```

Ouvrez **https://127.0.0.1:8000**

> Pour connaître les pseudos des membres :
> `php bin/console dbal:run-sql "SELECT pseudo FROM user WHERE pseudo != 'admin'"`

---

## 1. 🔐 Inscription & Connexion

| # | Action | Résultat attendu |
|---|--------|------------------|
| 1.1 | Cliquer sur **Inscription**, remplir le formulaire avec un nouveau pseudo | Compte créé, redirection / connexion |
| 1.2 | Se déconnecter, cliquer sur **Connexion** | Formulaire de login |
| 1.3 | Se connecter avec `admin` / `admin123` | Connecté en tant qu'admin ; le menu affiche **🛡️ Admin** |
| 1.4 | Se déconnecter | Retour à l'accueil, menu affiche Connexion/Inscription |

---

## 2. 🌍 Multilingue (FR / EN)

| # | Action | Résultat attendu |
|---|--------|------------------|
| 2.1 | Cliquer sur le bouton **🇬🇧 EN** dans la barre de navigation | Le menu passe en anglais (Home, Cactus, Login…) |
| 2.2 | Cliquer sur **🇫🇷 FR** | Retour en français |
| 2.3 | Connecté : aller dans **Préférences**, choisir English | La langue est mémorisée même après reconnexion |

---

## 3. 🎨 Thème clair / sombre

| # | Action | Résultat attendu |
|---|--------|------------------|
| 3.1 | Cliquer sur le sélecteur de thème (barre de nav) | Bascule clair ↔ sombre instantanément |
| 3.2 | Connecté : **Préférences → Mode Sombre** | Thème sombre appliqué partout |
| 3.3 | Se déconnecter / reconnecter | Le thème choisi est conservé (stocké en base) |

---

## 4. 🔍 Recherche avancée

Aller sur **Cactus** (catalogue).

| # | Action | Résultat attendu |
|---|--------|------------------|
| 4.1 | Saisir un nom partiel dans **Nom** → Rechercher | Seuls les cactus correspondants s'affichent |
| 4.2 | Choisir une **Catégorie** → Rechercher | Filtrage par catégorie |
| 4.3 | Saisir **Prix min** et **Prix max** | Filtrage par fourchette de prix |
| 4.4 | Choisir un **Niveau de soin** (Facile…) | Filtrage par soin |
| 4.5 | Choisir **Trier par → Prix croissant** | Résultats triés du moins cher au plus cher |
| 4.6 | Combiner plusieurs critères | Les filtres se cumulent |
| 4.7 | Cliquer sur **Réinitialiser** | Tous les cactus réapparaissent |

---

## 5. ❤️ Favoris

Connecté en tant que **membre** (`password`).

| # | Action | Résultat attendu |
|---|--------|------------------|
| 5.1 | Ouvrir un cactus (**Voir**), cliquer **❤️ Ajouter aux favoris** | Le bouton devient **💔 Retirer des favoris** |
| 5.2 | Aller dans **Mon profil** → section **Favoris** | Le cactus y figure |
| 5.3 | Retirer des favoris | Il disparaît de la liste |

---

## 6. 🛒 Achat & Commandes

Connecté en tant que **membre**.

| # | Action | Résultat attendu |
|---|--------|------------------|
| 6.1 | Ouvrir un cactus **dont vous n'êtes PAS le vendeur** | Bouton **🛒 Acheter** visible |
| 6.2 | Cliquer **Acheter**, saisir une adresse, **Confirmer** | Page de confirmation de commande |
| 6.3 | Aller dans **Mes commandes** (`/commande`) | La commande apparaît avec statut « en attente » |
| 6.4 | Ouvrir votre propre annonce | Le bouton Acheter est **absent** |

---

## 7. 💬 Commentaires & ⭐ Notation des vendeurs

Connecté en tant que **membre**.

| # | Action | Résultat attendu |
|---|--------|------------------|
| 7.1 | Ouvrir un cactus, cliquer sur le **pseudo du vendeur** | Page du vendeur |
| 7.2 | Cliquer sur les **étoiles** puis **Enregistrer** | Note enregistrée, moyenne mise à jour |
| 7.3 | Écrire un commentaire → **Publier** | Le commentaire apparaît dans la liste |
| 7.4 | Supprimer son propre commentaire | Le commentaire disparaît |
| 7.5 | Aller sur sa propre page vendeur | Pas de formulaire (on ne se note/commente pas soi-même) |

---

## 8. 👮 Gestion des rôles (Administrateur)

Connecté en tant que **`admin`**.

| # | Action | Résultat attendu |
|---|--------|------------------|
| 8.1 | Cliquer **🛡️ Admin** → **Utilisateurs** | Liste de tous les comptes |
| 8.2 | Cliquer **Modifier rôles** sur un membre, cocher **Administrateur**, enregistrer | Le rôle est mis à jour |
| 8.3 | Sur le catalogue Cactus : bouton **+ Publier une annonce** visible | Réservé à l'admin |
| 8.4 | Sur une fiche cactus : boutons **Modifier** / **Supprimer** visibles | Réservé à l'admin |

### Test des droits d'accès (membre)

| # | Action | Résultat attendu |
|---|--------|------------------|
| 8.5 | Connecté en **membre**, saisir `/admin` dans l'URL | **Accès refusé (403)** |
| 8.6 | Saisir `/cactus/new` dans l'URL | **Accès refusé (403)** |
| 8.7 | Visiteur non connecté, saisir `/profile` | Redirection vers la **connexion** |

---

## 9. 🌵 CRUD (Administrateur)

Connecté en tant que **`admin`**.

| # | Action | Résultat attendu |
|---|--------|------------------|
| 9.1 | **Cactus → + Publier une annonce**, remplir, valider | Nouveau cactus dans le catalogue |
| 9.2 | Ouvrir le cactus créé → **Modifier**, changer le prix | Prix mis à jour |
| 9.3 | **Supprimer** le cactus | Il disparaît du catalogue |
| 9.4 | Répéter pour **Pots**, **Catégories**, **Terreaux** | CRUD fonctionnel pour chaque entité |

---

## 10. 👤 Espace personnel & compte

Connecté en tant que **membre**.

| # | Action | Résultat attendu |
|---|--------|------------------|
| 10.1 | **Mon profil** | Affiche nom, pseudo, rôles, favoris, commandes |
| 10.2 | **Supprimer mon compte** → confirmer | Compte supprimé, déconnexion |

---

## ✅ Checklist finale

- [ ] Inscription / connexion / déconnexion
- [ ] Multilingue FR ↔ EN
- [ ] Thème clair ↔ sombre (persistant)
- [ ] Recherche avancée (critères indépendants + combinés)
- [ ] Favoris
- [ ] Achat + historique des commandes
- [ ] Commentaires sur vendeurs
- [ ] Notation des vendeurs
- [ ] Gestion des rôles (admin)
- [ ] Contrôle d'accès (403 / redirection)
- [ ] CRUD complet (cactus, pots, catégories, terreaux)
- [ ] Suppression de compte

Si toutes les cases sont cochées, l'application répond au cahier des charges. 🎉
