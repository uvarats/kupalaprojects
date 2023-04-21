<?php

namespace App\Twig\Runtime;

use App\Entity\User;
use Twig\Extension\RuntimeExtensionInterface;

class UserTwigExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function shortFullName(User $user): string
    {
        $lastName = $user->getLastName();
        $firstName = $user->getFirstName();
        $middleName = $user->getMiddleName();



        $shortFullName = $lastName;
    }
}
