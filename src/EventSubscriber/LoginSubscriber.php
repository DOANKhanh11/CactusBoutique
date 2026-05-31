<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Service\ThemeService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginSubscriber implements EventSubscriberInterface
{
    public function __construct(private ThemeService $themeService) {}

    public static function getSubscribedEvents(): array
    {
        return [LoginSuccessEvent::class => 'onLoginSuccess'];
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $user = $event->getAuthenticatedToken()->getUser();
        if (!$user instanceof User) {
            return;
        }

        $language = $this->themeService->getUserLanguage($user);
        $event->getRequest()->getSession()->set('_locale', $language);
    }
}
