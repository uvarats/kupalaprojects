<?php

declare(strict_types=1);

namespace App\Controller\Project;

use App\Entity\Project;
use App\Feature\Import\Repository\ProjectImportRepository;
use App\Feature\Import\Service\SyncParticipantsImporter;
use App\Feature\Project\Security\ProjectVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/project/{id}/participants/import', name: 'app_project_participants_import')]
final class ParticipantsImportAction extends AbstractController
{
    public function __invoke(
        Project $project,
        ProjectImportRepository $importRepository,
        SyncParticipantsImporter $participantsImporter,
    ): Response
    {
        $import = $importRepository->findOneBy(['project' => $project]);
        $participantsImporter->import($import);

        $this->denyAccessUnlessGranted(ProjectVoter::IS_PROJECT_OWNER, $project);

        return $this->render('project/participant/import.html.twig', ['project' => $project]);
    }
}
