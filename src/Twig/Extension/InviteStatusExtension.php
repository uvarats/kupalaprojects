<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\Twig\Runtime\InviteStatusExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class InviteStatusExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('statusHtml', [InviteStatusExtensionRuntime::class, 'toHtml'])
        ];
    }
}
