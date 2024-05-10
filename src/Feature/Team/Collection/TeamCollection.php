<?php

declare(strict_types=1);

namespace App\Feature\Team\Collection;

use App\Collection\Collection;
use App\Entity\Team;

/**
 * @extends Collection<Team>
 */
final class TeamCollection extends Collection
{
    #[\Override]
    public function getType(): string
    {
        return Team::class;
    }
}
