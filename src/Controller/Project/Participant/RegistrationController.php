<?php

declare(strict_types=1);

namespace App\Controller\Project\Participant;

use App\Entity\Participant;
use App\Entity\Project;
use App\Form\Project\ParticipantType;
use App\Service\Project\ParticipantService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Turbo\TurboBundle;

final class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly ParticipantService $participantService,
    ) {}

    #[Route('/project/{id}/registration/individual', name: 'app_project_participant_registration')]
    public function __invoke(Project $project, Request $request): Response
    {
        $participant = new Participant();
        $participant->setProject($project);

        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->participantService->handleParticipantRegistration($participant);

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
