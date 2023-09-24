<?php

declare(strict_types=1);

namespace App\Service\Util;

use Nette\Utils\Random;

final readonly class PasswordGeneratorService
{
    public function getRandomPassword(int $length = 10): string
    {
        return Random::generate($length);
    }
}
