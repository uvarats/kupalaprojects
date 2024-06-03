<?php

declare(strict_types=1);

namespace App\Feature\Import\Collection;

use App\Collection\Collection;
use App\Feature\Import\ValueObject\ParticipantImportError;

/**
 * @extends Collection<ParticipantImportError>
 */
final class ParticipantImportErrorCollection extends Collection
{

    public function getType(): string
    {
        return ParticipantImportError::class;
    }
}
