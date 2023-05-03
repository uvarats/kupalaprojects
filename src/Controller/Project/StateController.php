<?php

namespace App\Controller\Project;

use App\Entity\Project;
use App\Enum\ProjectTransitionEnum;
use App\Security\Voter\FestivalVoter;
use App\Service\Project\ProjectService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\EnumRequirement;
use Symfony\UX\Turbo\TurboBundle;

final class StateController extends AbstractController
{
    public function __construct(
        private readonly ProjectService $projectService,
    ) {
    }

    #[Route(
        path: '/project/{id}/transition/{transition}',
        name: 'app_project_transition',
        requirements: [
            'transition' => new EnumRequirement(
                ProjectTransitionEnum::class
            )
        ])
    ]
    public function __invoke(
        #[MapEntity(expr: 'repository.eagerLoad(id)')]
        Project $project,
        ProjectTransitionEnum $transition,
        Request $request
    ): Response {
        $festival = $project->getFestival();
        $this->denyAccessUnlessGranted(
            FestivalVoter::IS_ORGANIZATION_COMMITTEE_MEMBER,
            $festival
        );

        $this->projectService->makeTransition($project, $transition);

        return $this->projectTransitionResponse($project, $request);
    }

    private function projectTransitionResponse(
        Project $project,
        Request $request
    ): Response {
        if ($request->headers->get('Turbo-Frame') !== null) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return $this->render('festival/_project_card_stream.html.twig', [
                'project' => $project
            ]);
        }

        $festival = $project->getFestival();
        return $this->redirectToRoute('app_festival_projects', [
            'id' => (string)$festival->getId()
        ]);
    }
}
