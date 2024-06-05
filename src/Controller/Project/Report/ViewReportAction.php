<?php

declare(strict_types=1);

namespace App\Controller\Project\Report;

use App\Entity\Project;
use App\Feature\Project\Security\ProjectReportVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/project/{id:project}/report/view', name: 'app_project_report_view')]
final class ViewReportAction extends AbstractController
{
    public function __invoke(Project $project): Response
    {
        $this->denyAccessUnlessGranted(ProjectReportVoter::CAN_VIEW_EXISTING_REPORT, $project);

        return $this->render('project/report/view.html.twig', ['project' => $project]);
    }
}
