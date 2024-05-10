<?php

declare(strict_types=1);

namespace App\Feature\Team\Collection;

use App\Collection\Collection;
use App\Entity\TeamInvite;

/**
 * @extends Collection<TeamInvite>
 */
final class TeamInviteCollection extends Collection
{
    #[\Override]
    public function getType(): string
    {
        return TeamInvite::class;
    }
}
