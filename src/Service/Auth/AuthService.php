<?php

declare(strict_types=1);

namespace App\Service\Auth;

use App\Entity\User;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;

final readonly class AuthService
{
    public function __construct(
        private LoginLinkHandlerInterface $loginLinkHandler,
    ) {

    }

    public function getLoginLink(User $user): string
    {
        $loginLinkDetails = $this->loginLinkHandler->createLoginLink($user);

        return $loginLinkDetails->getUrl();
    }
}
