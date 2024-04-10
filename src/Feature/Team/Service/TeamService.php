<?php

declare(strict_types=1);

namespace App\Feature\Team\Service;

use App\Entity\Participant;
use App\Entity\Team;
use App\Feature\Project\Interface\ProjectRepositoryInterface;
use App\Feature\Team\Dto\CreateTeamRequest;
use Doctrine\ORM\EntityManagerInterface;

final readonly class TeamService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ProjectRepositoryInterface $projectRepository,
    ) {}

    public function createTeam(CreateTeamRequest $createRequest): Team
    {
        $projectId = $createRequest->getProjectId();
        $project = $this->projectRepository->findOneById($projectId);

        $owner = $createRequest->getOwner();
        $teamOwner = Participant::make(
            project: $project,
            name: $owner->getName(),
            educationEstablishment: $owner->getEducationEstablishment(),
            email: $owner->getEmail()->toString(),
        );

        $team = Team::create(
            name: $createRequest->getTeamName(),
            creator: $teamOwner,
            project: $project,
        );

        $participants = $createRequest->getParticipants();

        foreach ($participants as $participant) {
            $participantEntity = Participant::make(
                project: $project,
                name: $participant->getName(),
                educationEstablishment: $participant->getEducationEstablishment(),
                email: $participant->getEmail()->toString(),
            );

            // cascade persist is set on relation. it is a little strange, that participant is owning team
            //$this->entityManager->persist($participantEntity);
            $team->addParticipant($participantEntity);
        }

        $this->entityManager->persist($team);
        $this->entityManager->flush();

        return $team;
    }
}
