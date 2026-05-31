<?php

namespace App\EventSubscriber;

use App\Service\ThemeService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{
    public function __construct(private ThemeService $themeService) {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        // Priority: session locale → user DB preference → default
        $locale = $request->getSession()->get('_locale');

        if (!$locale) {
            try {
                $locale = $this->themeService->getUserLanguage();
            } catch (\Throwable) {
                $locale = 'fr';
            }
        }

        if (in_array($locale, ['fr', 'en'])) {
            $request->setLocale($locale);
        }
    }
}
