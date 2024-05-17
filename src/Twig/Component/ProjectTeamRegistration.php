<?php

declare(strict_types=1);

namespace App\Twig\Component;

use App\Entity\Project;
use App\Feature\Project\Dto\ProjectTeamData;
use App\Feature\Project\Form\ProjectTeamType;
use App\Feature\Project\Service\ProjectTeamService;
use App\Feature\Team\Security\TeamVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class ProjectTeamRegistration extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ?ProjectTeamData $data = null;

    #[LiveProp]
    public Project $project;

    public function __construct(
        private readonly ProjectTeamService $teamService,
    ) {}

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(ProjectTeamType::class, $this->data, ['project' => $this->project]);
    }

    #[LiveAction]
    public function save(): Response
    {
        $this->submitForm();

        $data = $this->getForm()->getData();
        assert($data instanceof ProjectTeamData);

        $team = $data->getTeam();

        $this->denyAccessUnlessGranted(TeamVoter::CAN_SUBMIT_TEAM_FOR_PROJECT, $team);
        $this->teamService->submitTeam($this->project, $team);

        return $this->redirectToRoute('app_projects');
    }
}
