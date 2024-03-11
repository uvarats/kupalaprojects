<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\Twig\Runtime\EnumExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EnumExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('enum', [EnumExtensionRuntime::class, 'enum']),
        ];
    }
}
