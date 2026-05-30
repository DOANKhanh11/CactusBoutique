# 🎨 Résumé - Système de Thème Implémenté

## 📋 Ce qui a été créé

### 1️⃣ **Entités de données**
- `UserPreference` - Stocke les préférences (thème, langue) pour chaque utilisateur
- Liée à `User` par une relation 1-1

### 2️⃣ **Logique métier**
- `ThemeService` - Service pour gérer les thèmes
  - Récupérer/définir thème utilisateur
  - Récupérer/définir langue utilisateur
  - Gestion des defaults

### 3️⃣ **Interface utilisateur**
- Page `/preferences` - Pour changer thème et langue
- `theme_switcher` - Composant dans navbar
- Boutons ☀️ (light) et 🌙 (dark)

### 4️⃣ **Styling**
- CSS variables pour 2 thèmes :
  - **Mode Clair** : Fond clair, texte foncé
  - **Mode Sombre** : Fond foncé, texte clair
- Transitions fluides entre thèmes

### 5️⃣ **Persistance**
- **Client** : localStorage (immédiat)
- **Serveur** : user_preference table (pour utilisateurs connectés)
- **Système** : Détection automatique (prefers-color-scheme)

---

## 🎯 Flux d'utilisation

```
┌─────────────────────────────────────────┐
│  Page charge pour la première fois      │
│  ┌─────────────────────────────────────┐│
│  │ Script JS s'exécute                 ││
│  ├─────────────────────────────────────┤│
│  │ 1. Vérifier localStorage            ││
│  │    - Si trouvé → utiliser cette val ││
│  │    - Si pas trouvé → étape 2        ││
│  │                                     ││
│  │ 2. Vérifier préférence système      ││
│  │    - prefers-color-scheme: dark     ││
│  │    - prefers-color-scheme: light    ││
│  │                                     ││
│  │ 3. Défaut: 'light'                  ││
│  │                                     ││
│  │ 4. Appliquer: <html data-theme="...">
│  │                                     ││
│  │ 5. CSS variables s'activent ✅      ││
│  └─────────────────────────────────────┘│
└─────────────────────────────────────────┘
            ↓
        Utilisateur clique sur 🌙
            ↓
┌─────────────────────────────────────────┐
│  toggleTheme('dark') est appelé          │
│  ┌─────────────────────────────────────┐│
│  │ 1. HTML: data-theme → 'dark'        ││
│  │ 2. CSS variables changent           ││
│  │ 3. Animations/transitions ✨        ││
│  │ 4. localStorage → 'dark'            ││
│  │ 5. API appel pour sauvegarder      ││
│  └─────────────────────────────────────┘│
└─────────────────────────────────────────┘
```

---

## 🔄 Architecture système

```
                    ┌─────────────────────────────┐
                    │   INTERFACE UTILISATEUR     │
                    ├─────────────────────────────┤
                    │ • Navbar (theme switcher)   │
                    │ • Page /preferences         │
                    │ • Boutons 🌙 ☀️            │
                    └─────────────────────────────┘
                              ↓
                    ┌─────────────────────────────┐
                    │   CONTRÔLEUR (Routes)       │
                    ├─────────────────────────────┤
                    │ PreferenceController        │
                    │ • GET /preferences          │
                    │ • POST /preferences/theme   │
                    │ • POST /preferences/api/*   │
                    └─────────────────────────────┘
                              ↓
                    ┌─────────────────────────────┐
                    │   SERVICE (Logique)         │
                    ├─────────────────────────────┤
                    │ ThemeService                │
                    │ • getUserTheme()            │
                    │ • setUserTheme()            │
                    │ • getUserLanguage()         │
                    │ • setUserLanguage()         │
                    └─────────────────────────────┘
                              ↓
                    ┌─────────────────────────────┐
                    │   REPOSITORY (Données)      │
                    ├─────────────────────────────┤
                    │ UserPreferenceRepository    │
                    │ • findOneBy()               │
                    │ • save()                    │
                    │ • remove()                  │
                    └─────────────────────────────┘
                              ↓
                    ┌─────────────────────────────┐
                    │   DATABASE                  │
                    ├─────────────────────────────┤
                    │ user_preference table       │
                    │ • id (primary)              │
                    │ • user_id (foreign)         │
                    │ • theme (light|dark)        │
                    │ • language (fr|en)          │
                    │ • created_at, updated_at    │
                    └─────────────────────────────┘
```

---

## 💾 CSS Variables Disponibles

### 🔆 **Mode Light** (`data-theme="light"`)
```
--bg-primary: #f7f5f0;       Fond principal
--bg-secondary: #fff;        Cartes, boîtes
--bg-tertiary: #f0ede6;      Entêtes de tableau
--text-primary: #333;        Texte principal
--text-secondary: #7a7a6e;   Texte secondaire
--text-tertiary: #6b6b5e;    Texte léger
--border-color: #f2f0ea;     Bordures
--menu-bg: #2f4f2f;          Navbar
--link-color: #5a8a5a;       Liens
```

### 🌙 **Mode Dark** (`data-theme="dark"`)
```
--bg-primary: #1a1a1a;       Fond principal
--bg-secondary: #2d2d2d;     Cartes, boîtes
--bg-tertiary: #3a3a3a;      Entêtes
--text-primary: #e0e0e0;     Texte principal
--text-secondary: #b0b0b0;   Texte secondaire
--text-tertiary: #a0a0a0;    Texte léger
--border-color: #404040;     Bordures
--menu-bg: #1a1a1a;          Navbar
--link-color: #7fb069;        Liens
```

---

## 🚀 Routes disponibles

| Méthode | URL | Description |
|---------|-----|-------------|
| GET | `/preferences` | Page des préférences |
| POST | `/preferences/theme/light` | Passer en mode clair |
| POST | `/preferences/theme/dark` | Passer en mode sombre |
| POST | `/preferences/language/fr` | Français |
| POST | `/preferences/language/en` | English |
| POST | `/preferences/api/theme/{light\|dark}` | API - définir thème |
| POST | `/preferences/api/theme/toggle` | API - basculer thème |
| GET | `/preferences/api/theme` | API - récupérer thème courant |

---

## 📂 Fichiers créés/modifiés

### ✨ **Nouveaux fichiers**
```
src/Entity/UserPreference.php
src/Repository/UserPreferenceRepository.php
src/Service/ThemeService.php
src/Controller/PreferenceController.php
migrations/Version20260530091000.php
templates/components/theme_switcher.html.twig
templates/preference/index.html.twig
THEME_SYSTEM.md
QUICKSTART_THEME.md
```

### 🔧 **Fichiers modifiés**
```
templates/base.html.twig (CSS variables + script)
templates/menu/home.html.twig (ajout theme switcher)
```

---

## ✅ Checklist de vérification

- [x] Entité UserPreference créée
- [x] Relation User-UserPreference établie
- [x] Repository implémenté
- [x] Service ThemeService créé
- [x] Contrôleur avec toutes les routes
- [x] CSS variables pour les 2 thèmes
- [x] Script JavaScript de gestion
- [x] localStorage implémenté
- [x] Composant theme_switcher dans navbar
- [x] Page de préférences créée
- [x] Migration de base de données
- [x] Documentation complète

---

## 🎓 Comment l'utiliser

### Pour les utilisateurs
1. Cliquer sur 🌙 ou ☀️ dans le coin droit de la navbar
2. Aller à `/preferences` pour plus d'options
3. Choisir thème et langue
4. Préférences sauvegardées automatiquement

### Pour les développeurs
1. Utiliser CSS variables dans les stylesheets
2. Utiliser `ThemeService` dans les contrôleurs
3. Utiliser `toggleTheme()` en JavaScript
4. L'adaptation au thème est automatique

---

## 🔗 Documentation complète

Voir [THEME_SYSTEM.md](THEME_SYSTEM.md) pour la documentation technique détaillée.
Voir [QUICKSTART_THEME.md](QUICKSTART_THEME.md) pour le guide de démarrage rapide.
