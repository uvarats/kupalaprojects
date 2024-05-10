<?php

declare(strict_types=1);

namespace App\Feature\Participant\Collection;

use App\Collection\Collection;
use App\Entity\Participant;

/**
 * @extends Collection<Participant>
 */
final class ParticipantCollection extends Collection
{
    public function getType(): string
    {
        return Participant::class;
    }
}
