# 🚀 Guide de Démarrage Rapide - Système de Thème

## Installation

### 1. Appliquer la migration
```bash
cd /Users/dangvankhanhdoan/CactusBoutique
php bin/console doctrine:migrations:migrate
```

### 2. Effacer le cache
```bash
php bin/console cache:clear
```

## Tests

### Test 1 : Theme Switcher dans la Navbar
1. Allez sur `http://localhost:8000/` (ou votre URL locale)
2. Regardez le coin droit de la barre de navigation
3. Vous verrez les boutons ☀️ (clair) et 🌙 (sombre)
4. Cliquez pour basculer entre les thèmes
5. Le thème devrait changer immédiatement
6. Rafraîchissez la page - le thème persiste

### Test 2 : Page des Préférences
1. Connectez-vous à votre compte utilisateur
2. Cliquez sur "⚙️ Préférences" dans la barre de navigation (coin droit)
3. Vous verrez deux sections : "Thème" et "Langue"
4. Cliquez sur les boutons pour changer
5. Les modifications sont sauvegardées en base de données

### Test 3 : Persistance localStorage
1. Ouvrez la DevTools (F12)
2. Allez dans Application → Storage → Local Storage
3. Cherchez la clé `cactus_boutique_theme`
4. Changez le thème et vérifiez la valeur
5. Elle devrait être 'light' ou 'dark'

### Test 4 : Détection système
1. Allez dans Settings de votre navigateur/système
2. Changez le thème préféré du système (light/dark)
3. Ouvrez une nouvelle fenêtre privée
4. Le thème devrait correspondre à votre préférence système

### Test 5 : API Endpoints
```bash
# Récupérer le thème courant
curl http://localhost:8000/preferences/api/theme

# Changer le thème (API)
curl -X POST http://localhost:8000/preferences/api/theme/dark

# Basculer le thème (API)
curl -X POST http://localhost:8000/preferences/api/theme/toggle
```

## Utilisation dans le code

### Dans un template Twig
```twig
<div style="background: var(--bg-primary); color: var(--text-primary);">
    {{ 'Contenu qui s\'adapte au thème' }}
</div>
```

### Dans un contrôleur PHP
```php
public function myAction(ThemeService $themeService): Response
{
    $currentTheme = $themeService->getUserTheme();
    // Retourne: 'light' ou 'dark'
    
    return $this->render('template.html.twig', [
        'theme' => $currentTheme,
    ]);
}
```

### En JavaScript
```javascript
// Récupérer le thème courant
const theme = document.documentElement.getAttribute('data-theme');

// Changer le thème
toggleTheme('dark');
toggleTheme('light');

// Basculer
toggleTheme(); // light → dark ou dark → light
```

## Personnaliser les couleurs

### Ajouter une nouvelle variable CSS

1. Ouvrez `templates/base.html.twig`
2. Trouvez la section "CSS Variables for Theming"
3. Ajoutez votre variable pour light:
```css
:root[data-theme="light"] {
    --my-color: #ffffff;
}
```

4. Ajoutez votre variable pour dark:
```css
:root[data-theme="dark"] {
    --my-color: #000000;
}
```

5. Utilisez-la dans votre CSS:
```css
.element {
    background: var(--my-color);
}
```

## Troubleshooting

### Le thème ne change pas
- Vérifiez que la migration a été exécutée
- Videz le cache du navigateur (Ctrl+F5)
- Vérifiez la console pour les erreurs

### Le localStorage n'est pas sauvegardé
- Vérifiez que les cookies/localStorage sont activés
- Essayez en mode incognito (pour exclure les extensions)

### Les couleurs ne correspondent pas
- Assurez-vous que toutes les variables CSS utilisent `var(--nom-variable)`
- Vérifiez que la variable CSS est définie pour les deux thèmes
- Vérifiez l'ordre des CSS (les variables doivent être définies en premier)

## Architecture résumée

```
User Interface (navbar + preferences page)
           ↓
PreferenceController (routes)
           ↓
ThemeService (business logic)
           ↓
UserPreferenceRepository (data access)
           ↓
user_preference (database)
           ↓
localStorage (client-side cache)
           ↓
CSS variables (theming)
```

## Fichiers modifiés/créés

- ✅ `src/Entity/UserPreference.php` (nouveau)
- ✅ `src/Repository/UserPreferenceRepository.php` (nouveau)
- ✅ `src/Service/ThemeService.php` (nouveau)
- ✅ `src/Controller/PreferenceController.php` (nouveau)
- ✅ `migrations/Version20260530091000.php` (nouvelle)
- ✅ `templates/base.html.twig` (modifié)
- ✅ `templates/menu/home.html.twig` (modifié)
- ✅ `templates/components/theme_switcher.html.twig` (nouveau)
- ✅ `templates/preference/index.html.twig` (nouveau)
- ✅ `THEME_SYSTEM.md` (documentation)

## Prochaines étapes optionnelles

1. **Ajouter d'autres thèmes** (ex: sepia, haut contraste)
2. **Synchroniser avec préférences système** (à la journée)
3. **Ajouter éditeur de couleurs** dans les paramètres
4. **Support des transitions animées** entre thèmes
5. **Intégrer avec système de langue** pour localisation
