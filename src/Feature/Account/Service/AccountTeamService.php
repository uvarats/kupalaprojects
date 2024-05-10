<?php

declare(strict_types=1);

namespace App\Feature\Account\Service;

use App\Entity\Team;
use App\Feature\Account\Dto\CreateTeamRequest;
use Doctrine\ORM\EntityManagerInterface;

final readonly class AccountTeamService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    public function create(CreateTeamRequest $request): Team
    {
        $name = $request->getName();
        $creator = $request->getCreator();

        $team = Team::create($name, $creator);

        // team participant has cascade persist, so, when we are persisting team, participant also persisted
        $this->entityManager->persist($team);
        $this->entityManager->flush();

        return $team;
    }
}
