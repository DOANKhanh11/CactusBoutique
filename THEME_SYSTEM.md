# 🎨 Système de Thème - Guide Complet

## Vue d'ensemble

Le système de thème implémente un mode **clair (light)** et **sombre (dark)** avec :
- **Persistance** : Les préférences sont sauvegardées en base de données et localStorage
- **Détection** : Support de `prefers-color-scheme` du système
- **Transitions** : Changements fluides de thème
- **Flexibilité** : CSS variables pour personnalisation facile

---

## Architecture

### 1. **Entité UserPreference** (`src/Entity/UserPreference.php`)
Stocke les préférences de l'utilisateur :
- `theme` : 'light' ou 'dark'
- `language` : 'fr' ou 'en'
- Liée au User par une relation OneToOne

### 2. **Service ThemeService** (`src/Service/ThemeService.php`)
Gère la logique métier :
- `getUserTheme(?User)` : Récupère le thème de l'utilisateur
- `setUserTheme(string, ?User)` : Change le thème
- `getUserLanguage(?User)` : Récupère la langue
- `setUserLanguage(string, ?User)` : Change la langue

### 3. **Contrôleur PreferenceController** (`src/Controller/PreferenceController.php`)
Routes disponibles :
- `GET /preferences` : Affiche la page des préférences
- `POST /preferences/theme/{theme}` : Change le thème
- `POST /preferences/language/{language}` : Change la langue
- `POST /preferences/api/theme/toggle` : API toggle thème
- `POST /preferences/api/theme/{theme}` : API set thème
- `GET /preferences/api/theme` : API get thème courant

### 4. **CSS Variables System**
Définies dans `templates/base.html.twig` :

**Mode Light** (`data-theme="light"`):
```css
--bg-primary: #f7f5f0;      /* Fond principal */
--bg-secondary: #fff;        /* Fond secondaire */
--bg-tertiary: #f0ede6;      /* Fond tertiaire */
--text-primary: #333;        /* Texte principal */
--text-secondary: #7a7a6e;   /* Texte secondaire */
```

**Mode Dark** (`data-theme="dark"`):
```css
--bg-primary: #1a1a1a;       /* Fond principal */
--bg-secondary: #2d2d2d;     /* Fond secondaire */
--bg-tertiary: #3a3a3a;      /* Fond tertiaire */
--text-primary: #e0e0e0;     /* Texte principal */
--text-secondary: #b0b0b0;   /* Texte secondaire */
```

---

## Utilisation

### Pour l'utilisateur
1. Accédez à `/preferences`
2. Cliquez sur "☀️ Mode Clair" ou "🌙 Mode Sombre"
3. Cliquez sur les boutons dans la barre de navigation

### Pour le développeur

#### JavaScript - Changer le thème
```javascript
// Basculer entre light et dark
toggleTheme();

// Définir un thème spécifique
toggleTheme('dark');
toggleTheme('light');
```

#### JavaScript - Lire le thème courant
```javascript
const currentTheme = document.documentElement.getAttribute('data-theme');
// Retourne: 'light' ou 'dark'
```

#### PHP - Contrôleur
```php
public function myAction(ThemeService $themeService): Response
{
    $theme = $themeService->getUserTheme(); // 'light' ou 'dark'
    $language = $themeService->getUserLanguage(); // 'fr' ou 'en'
    
    return $this->render('my_template.html.twig', [
        'theme' => $theme,
        'language' => $language,
    ]);
}
```

#### Twig - Utiliser les variables du thème
```twig
{# Appliquer le thème aux éléments #}
<div style="background: var(--bg-primary); color: var(--text-primary);">
    Contenu adapté au thème
</div>
```

---

## Ajouter une nouvelle couleur de thème

1. **Ajouter les variables CSS** dans `templates/base.html.twig` :
```css
:root[data-theme="light"] {
    --my-custom-color: #1234567;
}

:root[data-theme="dark"] {
    --my-custom-color: #abcdef0;
}
```

2. **Utiliser dans le CSS** :
```css
.my-element {
    color: var(--my-custom-color);
    transition: color var(--transition-speed);
}
```

---

## Stockage des préférences

### localStorage (Client)
- Clé: `cactus_boutique_theme`
- Valeur: 'light' ou 'dark'
- Chargé au démarrage de la page

### Base de données (Serveur)
- Table: `user_preference`
- Synchronisé lors d'un changement
- Utilisé si l'utilisateur est authentifié

### Hiérarchie de détection
1. Valeur de localStorage (si présente)
2. Préférence système (prefers-color-scheme)
3. Défaut: 'light'

---

## Migration de base de données

```bash
# Exécuter les migrations
php bin/console doctrine:migrations:migrate

# Vérifier la table créée
# user_preference (id, user_id, theme, language, created_at, updated_at)
```

---

## Endpoints API

### Récupérer le thème courant
```bash
GET /preferences/api/theme

# Réponse:
# {"theme": "light"}
```

### Changer le thème (API)
```bash
POST /preferences/api/theme/dark
# ou
POST /preferences/api/theme/light

# Réponse:
# {"success": true, "theme": "dark"}
```

### Basculer le thème (API)
```bash
POST /preferences/api/theme/toggle

# Réponse:
# {"success": true, "theme": "dark", "message": "Theme changed to dark mode"}
```

---

## Troubleshooting

### Le thème ne change pas
1. Vérifier la console du navigateur pour les erreurs
2. Vérifier que les fichiers sont inclus dans `base.html.twig`
3. Vérifier que `data-theme` est défini sur l'élément HTML

### Les couleurs ne s'appliquent pas
1. Vérifier que vous utilisez `var(--nom-couleur)`
2. Vérifier que la variable CSS est définie
3. Nettoyer le cache du navigateur

### Les préférences ne se sauvegardent pas
1. Vérifier que la migration est exécutée
2. Vérifier que l'utilisateur est authentifié
3. Vérifier les logs de l'application

---

## Améliorations futures possibles

- [ ] Ajouter plus de thèmes personnalisés
- [ ] Système de thème automatique (heure de la journée)
- [ ] Éditeur de thème dans les paramètres
- [ ] Export/import de thèmes
- [ ] Support de CSS personnalisé par utilisateur
