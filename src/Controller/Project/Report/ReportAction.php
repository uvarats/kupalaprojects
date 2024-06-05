<?php

declare(strict_types=1);

namespace App\Controller\Project\Report;

use App\Entity\Project;
use App\Feature\Project\Security\ProjectReportVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/project/{id}/report', name: 'app_project_report')]
final class ReportAction extends AbstractController
{
    public function __invoke(Project $project): Response
    {
        $this->denyAccessUnlessGranted(ProjectReportVoter::PROJECT_REPORTING_ALLOWED, $project);

        return $this->render('project/report/form.html.twig', ['project' => $project]);
    }
}
