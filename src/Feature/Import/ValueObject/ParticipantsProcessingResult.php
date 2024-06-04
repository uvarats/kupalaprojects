<?php

declare(strict_types=1);

namespace App\Feature\Import\ValueObject;

use App\Feature\Import\Collection\ParticipantImportErrorCollection;
use App\Feature\Participant\Collection\ParticipantCollection;

final readonly class ParticipantsProcessingResult
{
    public function __construct(
        private ParticipantCollection $newParticipants,
        private ParticipantCollection $rejectedParticipants,
    ) {}

    public function getNewParticipants(): ParticipantCollection
    {
        return $this->newParticipants;
    }

    public function getRejectedParticipants(): ParticipantCollection
    {
        return $this->rejectedParticipants;
    }

    public function getErrors(): ParticipantImportErrorCollection
    {
        return $this->errors;
    }
}
