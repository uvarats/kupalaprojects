<?php

declare(strict_types=1);

namespace App\Feature\Import\Collection;

use App\Collection\Collection;
use App\Feature\Import\Dto\ParticipantRow;

/**
 * @extends Collection<ParticipantRow>
 */
final class ParticipantRowCollection extends Collection
{

    public function getType(): string
    {
        return ParticipantRow::class;
    }
}
