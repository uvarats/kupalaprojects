<?php

declare(strict_types=1);

namespace App\Twig\Component;

use App\Entity\Project;
use App\Feature\Project\Collection\ProjectParticipantCollection;
use App\Feature\Project\Repository\ProjectParticipantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class ParticipantModeration extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp]
    public Project $project;

    #[LiveProp(writable: true)]
    public string $query = '';

    public function __construct(
        private readonly ProjectParticipantRepository $projectParticipantRepository,
    ) {}

    public function getParticipants(): ProjectParticipantCollection
    {
        return $this->projectParticipantRepository->searchParticipants(
            $this->project,
            $this->query,
        );
    }
}
