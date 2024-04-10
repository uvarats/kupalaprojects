<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\Twig\Runtime\ProjectExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ProjectExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('orientedOn', [ProjectExtensionRuntime::class, 'orientedOn'], ['is_safe' => ['html']]),
            new TwigFilter('subjects', [ProjectExtensionRuntime::class, 'subjects'], ['is_safe' => ['html']])
        ];
    }

    public function getFunctions(): array
    {
        return [];
    }
}
