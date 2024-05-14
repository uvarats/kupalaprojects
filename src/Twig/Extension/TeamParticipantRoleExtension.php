<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\Twig\Runtime\TeamParticipantRoleExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

final class TeamParticipantRoleExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('participantRoleEnum', [TeamParticipantRoleExtensionRuntime::class, 'toHtml'], ['is_safe' => ['html']]),
        ];
    }
}
