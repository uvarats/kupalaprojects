<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\Twig\Runtime\AcceptanceExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class AcceptanceExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('acceptanceHtml', [AcceptanceExtensionRuntime::class, 'toHtml'], ['is_safe' => ['html']]),
        ];
    }
}
