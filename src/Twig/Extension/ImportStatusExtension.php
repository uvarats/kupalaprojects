<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\Twig\Runtime\ImportStatusExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ImportStatusExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('translate_import_status', [ImportStatusExtensionRuntime::class, 'translateStatus']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('function_name', [ImportStatusExtensionRuntime::class, 'translateStatus']),
        ];
    }
}
