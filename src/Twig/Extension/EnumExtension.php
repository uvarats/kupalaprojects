<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\EnumExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
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
