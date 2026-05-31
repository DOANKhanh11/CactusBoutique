<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        // Priority 20 = after RouterListener (32) but early enough to affect translator
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $locale = $request->getSession()->get('_locale', 'fr');

        if (in_array($locale, ['fr', 'en'])) {
            $request->setLocale($locale);
        }
    }
}
