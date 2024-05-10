<?php

declare(strict_types=1);

namespace App\Feature\Account\Dto;

use App\Entity\Participant;

final readonly class CreateTeamRequest
{
    private function __construct(
        private string $name,
        private Participant $creator,
    ) {}

    public static function make(string $name, Participant $creator): CreateTeamRequest
    {
        if (empty($name)) {
            throw new \DomainException('Team name must not be empty');
        }

        return new self(name: $name, creator: $creator);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCreator(): Participant
    {
        return $this->creator;
    }
}
