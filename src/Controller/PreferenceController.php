<?php

namespace App\Controller;

use App\Service\ThemeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/preferences')]
class PreferenceController extends AbstractController
{
    public function __construct(
        private ThemeService $themeService,
    ) {
    }

    /**
     * Display user preferences page
     */
    #[Route('', name: 'app_preferences', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(Request $request): Response
    {
        $currentTheme = $this->themeService->getUserTheme();
        $currentLanguage = $this->themeService->getUserLanguage();

        return $this->render('preference/index.html.twig', [
            'theme' => $currentTheme,
            'language' => $currentLanguage,
        ]);
    }

    /**
     * Toggle theme between light and dark
     */
    #[Route('/theme/toggle', name: 'app_theme_toggle', methods: ['POST', 'GET'])]
    public function toggleTheme(Request $request): Response
    {
        $currentTheme = $this->themeService->getUserTheme();
        $newTheme = $currentTheme === 'light' ? 'dark' : 'light';

        try {
            $this->themeService->setUserTheme($newTheme);
            $this->addFlash('success', sprintf('Theme changed to %s mode', $newTheme));
        } catch (\Exception $e) {
            $this->addFlash('error', 'Failed to change theme: ' . $e->getMessage());
        }

        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer ?: $this->generateUrl('app_home'));
    }

    /**
     * Set specific theme
     */
    #[Route('/theme/{theme}', name: 'app_theme_set', methods: ['POST', 'GET'])]
    public function setTheme(string $theme, Request $request): Response
    {
        try {
            $this->themeService->setUserTheme($theme);
            $this->addFlash('success', sprintf('Theme changed to %s mode', $theme));
        } catch (\Exception $e) {
            $this->addFlash('error', 'Failed to change theme: ' . $e->getMessage());
        }

        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer ?: $this->generateUrl('app_home'));
    }

    /**
     * Set language preference
     */
    #[Route('/language/{language}', name: 'app_preferences_language', methods: ['POST', 'GET'])]
    public function setLanguage(string $language, Request $request): Response
    {
        try {
            $this->themeService->setUserLanguage($language);
            $request->getSession()->set('_locale', $language);
            $this->addFlash('success', sprintf('Language changed to %s', $language === 'fr' ? 'Français' : 'English'));
        } catch (\Exception $e) {
            $this->addFlash('error', 'Failed to change language: ' . $e->getMessage());
        }

        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer ?: $this->generateUrl('app_home'));
    }

    /**
     * API endpoint to toggle theme (returns JSON)
     */
    #[Route('/api/theme/toggle', name: 'app_api_theme_toggle', methods: ['POST'])]
    public function apiToggleTheme(): JsonResponse
    {
        try {
            $currentTheme = $this->themeService->getUserTheme();
            $newTheme = $currentTheme === 'light' ? 'dark' : 'light';
            $this->themeService->setUserTheme($newTheme);

            return $this->json([
                'success' => true,
                'theme' => $newTheme,
                'message' => sprintf('Theme changed to %s mode', $newTheme),
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * API endpoint to get current theme
     */
    #[Route('/api/theme', name: 'app_api_theme_get', methods: ['GET'])]
    public function apiGetTheme(): JsonResponse
    {
        return $this->json([
            'theme' => $this->themeService->getUserTheme(),
        ]);
    }

    /**
     * API endpoint to set theme
     */
    #[Route('/api/theme/{theme}', name: 'app_api_theme_set', methods: ['POST'])]
    public function apiSetTheme(string $theme): JsonResponse
    {
        try {
            $this->themeService->setUserTheme($theme);
            return $this->json([
                'success' => true,
                'theme' => $theme,
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
