<?php

declare(strict_types=1);

namespace App\Controller\Project\Participant;

use App\Dto\Form\Participant\ParticipantData;
use App\Entity\Project;
use App\Entity\User;
use App\Form\Project\ParticipantDataType;
use App\Security\Voter\ParticipantVoter;
use App\Service\Project\ParticipantService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\UX\Turbo\TurboBundle;

final class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly ParticipantService $participantService,
        private readonly EntityManagerInterface $entityManager,
    ) {}

    #[Route('/project/{id}/participant/submit', name: 'app_project_participant_submit')]
    #[IsGranted(ParticipantVoter::HAS_PARTICIPANT_DATA)]
    public function __invoke(Project $project, #[CurrentUser] User $user, Request $request): Response
    {
        $participant = $user->getParticipant();

        assert($participant !== null);

        $project->submitParticipant($participant);

        $this->entityManager->flush();

        $route = $request->headers->get('referer');
        return $this->redirect($route);
    }

    /**
     * @deprecated
     */
    #[Route('/project/{id}/registration/individual', name: 'app_project_participant_registration')]
    public function old(Project $project, Request $request): Response
    {
        $participantData = new ParticipantData();
        //$participantData->setProject($project);

        $form = $this->createForm(ParticipantDataType::class, $participantData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $participant = $this->participantService->handleParticipantRegistration($participantData, $project);

            if ($request->getPreferredFormat() === TurboBundle::STREAM_FORMAT) {
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

                return $this->render('project/participant/success.stream.html.twig', [
                    'participant' => $participant,
                ]);
            }

            return $this->render('project/participant/registration-success.html.twig', [
                'participant' => $participant
            ]);
        }

        return $this->render('project/participant/registration.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
        ]);
    }
}
