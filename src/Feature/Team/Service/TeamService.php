<?php

declare(strict_types=1);

namespace App\Feature\Team\Service;

use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;

final readonly class TeamService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}
    public function archive(Team $team): void
    {
        $team->archive();

        $this->entityManager->flush();
    }
}
