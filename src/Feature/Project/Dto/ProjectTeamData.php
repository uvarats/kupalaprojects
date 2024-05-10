<?php

declare(strict_types=1);

namespace App\Feature\Project\Dto;

use App\Entity\Team;
use Symfony\Component\Validator\Constraints as Assert;

class ProjectTeamData
{
    #[Assert\NotNull]
    private ?Team $team = null;

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): void
    {
        $this->team = $team;
    }
}
