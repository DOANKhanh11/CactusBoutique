# 📚 Exemples pratiques - Système de Thème

## 1️⃣ Utiliser le thème dans un template Twig

### Exemple simple
```twig
{# Créer un conteneur adapté au thème #}
<div style="
    background: var(--bg-secondary);
    color: var(--text-primary);
    padding: 20px;
    border-radius: 8px;
    border: 1px solid var(--border-color);
">
    <h2>Conteneur adapté au thème</h2>
    <p>Ce conteneur change automatiquement de couleur selon le thème</p>
</div>
```

### Exemple avec transition
```twig
<div style="
    background: var(--bg-secondary);
    color: var(--text-primary);
    transition: background var(--transition-speed), color var(--transition-speed);
    padding: 15px;
    border-radius: 6px;
">
    Animation fluide lors du changement de thème
</div>
```

### Exemple avec bouton
```twig
<button style="
    background: var(--heading-color);
    color: var(--bg-primary);
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    transition: transform 0.2s;
" 
onmouseover="this.style.transform='translateY(-2px)'"
onmouseout="this.style.transform='translateY(0)'">
    Bouton adapté au thème
</button>
```

---

## 2️⃣ Utiliser le thème en PHP/Contrôleur

### Exemple 1: Récupérer le thème et l'afficher
```php
<?php
namespace App\Controller;

use App\Service\ThemeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(ThemeService $themeService): Response
    {
        // Récupérer le thème de l'utilisateur connecté
        $theme = $themeService->getUserTheme();
        
        // Récupérer la langue
        $language = $themeService->getUserLanguage();
        
        return $this->render('dashboard/index.html.twig', [
            'theme' => $theme,
            'language' => $language,
        ]);
    }
}
```

### Exemple 2: Ajouter du CSS dynamique basé sur le thème
```php
<?php
namespace App\Controller;

use App\Service\ThemeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    #[Route('/report', name: 'app_report')]
    public function generateReport(ThemeService $themeService): Response
    {
        $theme = $themeService->getUserTheme();
        
        // Créer un PDF ou export avec les couleurs du thème
        $backgroundColor = $theme === 'dark' ? '#2d2d2d' : '#ffffff';
        $textColor = $theme === 'dark' ? '#e0e0e0' : '#333333';
        
        return $this->render('report/index.html.twig', [
            'bgColor' => $backgroundColor,
            'textColor' => $textColor,
        ]);
    }
}
```

---

## 3️⃣ Utiliser le thème en JavaScript

### Exemple 1: Détecter les changements de thème
```javascript
// Créer un observateur pour détecter les changements
const observer = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
        if (mutation.attributeName === 'data-theme') {
            const newTheme = document.documentElement.getAttribute('data-theme');
            console.log('Thème changé en:', newTheme);
            
            // Faire quelque chose quand le thème change
            if (newTheme === 'dark') {
                console.log('Mode sombre activé!');
                // Charger des images adaptées au thème
                updateImages('dark');
            } else {
                console.log('Mode clair activé!');
                updateImages('light');
            }
        }
    });
});

observer.observe(document.documentElement, {
    attributes: true,
    attributeFilter: ['data-theme']
});
```

### Exemple 2: Charger des ressources basées sur le thème
```javascript
// Charger des images différentes selon le thème
function updateImages(theme) {
    const images = document.querySelectorAll('[data-theme-aware]');
    
    images.forEach((img) => {
        if (theme === 'dark') {
            img.src = img.dataset.darkSrc;
        } else {
            img.src = img.dataset.lightSrc;
        }
    });
}

// Utilisation dans le HTML:
// <img data-theme-aware 
//      data-light-src="/images/logo-light.png"
//      data-dark-src="/images/logo-dark.png" />
```

### Exemple 3: Changer le thème par API
```javascript
// Fonction pour changer le thème via API
async function changeThemeViaAPI(newTheme) {
    try {
        const response = await fetch(`/preferences/api/theme/${newTheme}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            console.log(`Thème changé en: ${data.theme}`);
            // Mettre à jour l'UI locale
            document.documentElement.setAttribute('data-theme', newTheme);
        } else {
            console.error('Erreur:', data.error);
        }
    } catch (error) {
        console.error('Erreur lors du changement de thème:', error);
    }
}

// Utilisation
changeThemeViaAPI('dark');
```

### Exemple 4: Bouton custom pour basculer le thème
```html
<button id="theme-toggle-btn">
    <span class="light-icon">☀️</span>
    <span class="dark-icon">🌙</span>
</button>

<script>
document.getElementById('theme-toggle-btn').addEventListener('click', () => {
    toggleTheme(); // Fonction globale fournie par base.html.twig
});
</script>
```

---

## 4️⃣ Créer des styles spécifiques au thème

### Exemple 1: Classe CSS spéciale pour chaque thème
```css
/* Styles pour le mode clair */
[data-theme="light"] .card {
    background: white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

[data-theme="light"] .card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Styles pour le mode sombre */
[data-theme="dark"] .card {
    background: #2d2d2d;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
}

[data-theme="dark"] .card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
}
```

### Exemple 2: Utiliser des variables CSS avec fallback
```css
.element {
    background: var(--bg-secondary, #ffffff);
    color: var(--text-primary, #333333);
    border: 1px solid var(--border-color, #cccccc);
    transition: all var(--transition-speed, 0.3s) ease;
}
```

### Exemple 3: Animations adaptées au thème
```css
/* Animation différente selon le thème */
[data-theme="light"] .animated {
    animation: slideLight 0.5s ease;
}

[data-theme="dark"] .animated {
    animation: slideDark 0.5s ease;
}

@keyframes slideLight {
    from { 
        transform: translateX(-20px);
        opacity: 0;
        background: #f5f5f5;
    }
    to { 
        transform: translateX(0);
        opacity: 1;
        background: white;
    }
}

@keyframes slideDark {
    from { 
        transform: translateX(-20px);
        opacity: 0;
        background: #3a3a3a;
    }
    to { 
        transform: translateX(0);
        opacity: 1;
        background: #2d2d2d;
    }
}
```

---

## 5️⃣ Cas d'usage avancés

### Exemple 1: Auto-déterminer le thème à une heure spécifique
```javascript
// Basculer automatiquement en mode sombre après 18h
function autoThemeByTime() {
    const hour = new Date().getHours();
    
    if (hour >= 18 || hour < 6) {
        toggleTheme('dark');
    } else {
        toggleTheme('light');
    }
}

// Exécuter au chargement
autoThemeByTime();

// Vérifier toutes les heures
setInterval(autoThemeByTime, 60 * 60 * 1000);
```

### Exemple 2: Sauvegarder les préférences de thème personnalisées
```php
<?php
namespace App\Controller;

use App\Entity\User;
use App\Service\ThemeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ThemeCustomizationController extends AbstractController
{
    #[Route('/customize-theme', name: 'app_customize_theme', methods: ['POST'])]
    public function customizeTheme(
        Request $request,
        ThemeService $themeService,
        EntityManagerInterface $em
    ): Response {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Not authenticated'], 401);
        }
        
        $newTheme = $request->request->get('theme');
        
        // Valider
        if (!in_array($newTheme, ['light', 'dark'])) {
            return $this->json(['error' => 'Invalid theme'], 400);
        }
        
        // Sauvegarder
        $themeService->setUserTheme($newTheme);
        
        return $this->json(['success' => true, 'theme' => $newTheme]);
    }
}
```

### Exemple 3: Exporter le thème en JSON
```php
<?php
// Dans un contrôleur ou service
public function exportThemeConfig(): array
{
    return [
        'light' => [
            'bg-primary' => '#f7f5f0',
            'bg-secondary' => '#ffffff',
            'text-primary' => '#333333',
            'text-secondary' => '#7a7a6e',
        ],
        'dark' => [
            'bg-primary' => '#1a1a1a',
            'bg-secondary' => '#2d2d2d',
            'text-primary' => '#e0e0e0',
            'text-secondary' => '#b0b0b0',
        ]
    ];
}
```

---

## 📋 Résumé des fonctions/méthodes principales

### JavaScript
```javascript
toggleTheme()                    // Basculer light ↔ dark
toggleTheme('dark')             // Définir mode sombre
toggleTheme('light')            // Définir mode clair
```

### PHP (ThemeService)
```php
$themeService->getUserTheme()   // Récupère 'light' ou 'dark'
$themeService->setUserTheme('dark')  // Définit le thème
$themeService->getUserLanguage()     // Récupère 'fr' ou 'en'
$themeService->setUserLanguage('fr') // Définit la langue
```

### CSS Variables
```css
var(--bg-primary)       /* Fond principal */
var(--text-primary)     /* Texte principal */
var(--border-color)     /* Bordures */
/* ... et bien d'autres */
```

---

## 🐛 Debugging

```javascript
// Vérifier le thème courant
console.log(document.documentElement.getAttribute('data-theme'));

// Vérifier localStorage
console.log(localStorage.getItem('cactus_boutique_theme'));

// Forcer un thème (pour tester)
document.documentElement.setAttribute('data-theme', 'dark');
localStorage.setItem('cactus_boutique_theme', 'dark');
```
