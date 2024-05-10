<?php

declare(strict_types=1);

namespace App\Feature\Team\Collection;

use App\Collection\Collection;
use App\Entity\TeamParticipant;

/**
 * @extends Collection<TeamParticipant>
 */
final class TeamParticipantCollection extends Collection
{
    #[\Override]
    public function getType(): string
    {
        return TeamParticipant::class;
    }
}
