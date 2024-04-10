<?php

declare(strict_types=1);

namespace App\Feature\Team\Collection;

use App\Collection\Collection;
use App\Feature\Team\Dto\TeamParticipantData;

/**
 * @extends Collection<TeamParticipantData>
 */
final class TeamParticipantCollection extends Collection
{
    #[\Override]
    public function getType(): string
    {
        return TeamParticipantData::class;
    }
}
