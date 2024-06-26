<?php

declare(strict_types=1);

namespace App\Feature\Import\ValueObject;

use App\Feature\Import\Collection\ParticipantImportErrorCollection;
use App\Feature\Participant\Collection\ParticipantCollection;

final readonly class ParticipantImportReport
{
    public function __construct(
        private ParticipantImportErrorCollection $errors = new ParticipantImportErrorCollection(),
        private ParticipantCollection $newParticipants = new ParticipantCollection(),
        private ParticipantCollection $rejectedParticipants = new ParticipantCollection(),
    ) {}

    public function getErrors(): ParticipantImportErrorCollection
    {
        return $this->errors;
    }

    public function getNewParticipants(): ParticipantCollection
    {
        return $this->newParticipants;
    }

    public function getRejectedParticipants(): ParticipantCollection
    {
        return $this->rejectedParticipants;
    }
}
