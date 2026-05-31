<?php

namespace App\Twig;

use App\Service\ThemeService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function __construct(private ThemeService $themeService) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('user_theme', fn() => $this->themeService->getUserTheme()),
            new TwigFunction('user_language', fn() => $this->themeService->getUserLanguage()),
        ];
    }
}
