<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\ProjectStateExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ProjectStateExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('fancy', [ProjectStateExtensionRuntime::class, 'makeState']),
        ];
    }

    public function getFunctions(): array
    {
        return [];
    }
}
