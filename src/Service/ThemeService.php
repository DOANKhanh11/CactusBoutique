<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserPreference;
use App\Repository\UserPreferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class ThemeService
{
    public function __construct(
        private UserPreferenceRepository $preferenceRepository,
        private EntityManagerInterface $entityManager,
        private Security $security,
    ) {
    }

    /**
     * Get the current user's theme preference
     */
    public function getUserTheme(?User $user = null): string
    {
        if (!$user) {
            $user = $this->security->getUser();
        }

        if (!$user) {
            return 'light';
        }

        $preference = $this->preferenceRepository->findOneBy(['user' => $user]);

        if (!$preference) {
            $preference = new UserPreference();
            $preference->setUser($user);
            $preference->setTheme('light');
            $this->preferenceRepository->save($preference, true);
        }

        return $preference->getTheme();
    }

    /**
     * Set the user's theme preference
     */
    public function setUserTheme(string $theme, ?User $user = null): void
    {
        if (!$user) {
            $user = $this->security->getUser();
        }

        if (!$user) {
            throw new \RuntimeException('User must be authenticated to set theme preference');
        }

        if (!in_array($theme, ['light', 'dark'])) {
            throw new \InvalidArgumentException('Invalid theme. Must be "light" or "dark".');
        }

        $preference = $this->preferenceRepository->findOneBy(['user' => $user]);

        if (!$preference) {
            $preference = new UserPreference();
            $preference->setUser($user);
        }

        $preference->setTheme($theme);
        $preference->setUpdatedAt(new \DateTime());
        $this->preferenceRepository->save($preference, true);
    }

    /**
     * Get the current user's language preference
     */
    public function getUserLanguage(?User $user = null): string
    {
        if (!$user) {
            $user = $this->security->getUser();
        }

        if (!$user) {
            return 'fr';
        }

        $preference = $this->preferenceRepository->findOneBy(['user' => $user]);

        if (!$preference) {
            $preference = new UserPreference();
            $preference->setUser($user);
            $preference->setLanguage('fr');
            $this->preferenceRepository->save($preference, true);
        }

        return $preference->getLanguage();
    }

    /**
     * Set the user's language preference
     */
    public function setUserLanguage(string $language, ?User $user = null): void
    {
        if (!$user) {
            $user = $this->security->getUser();
        }

        if (!$user) {
            throw new \RuntimeException('User must be authenticated to set language preference');
        }

        if (!in_array($language, ['fr', 'en'])) {
            throw new \InvalidArgumentException('Invalid language. Must be "fr" or "en".');
        }

        $preference = $this->preferenceRepository->findOneBy(['user' => $user]);

        if (!$preference) {
            $preference = new UserPreference();
            $preference->setUser($user);
        }

        $preference->setLanguage($language);
        $preference->setUpdatedAt(new \DateTime());
        $this->preferenceRepository->save($preference, true);
    }
}
