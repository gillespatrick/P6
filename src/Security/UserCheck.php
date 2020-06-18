<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * This function is used to check if the users've activate them accounts after registration.
 */
class UserCheck implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof User) {
            return;
        }

        if (!$user->getActivate()) {
            throw new CustomUserMessageAuthenticationException('Account not activated ! Please check your email !');
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof User) {
            return;
        }
    }
}
