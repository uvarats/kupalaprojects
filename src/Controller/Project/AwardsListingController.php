<?php

declare(strict_types=1);

namespace App\Controller\Project;

use App\Entity\Project;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class AwardsListingController extends AbstractController
{
    #[Route('/project/{id}/awards', name: 'app_project_awards')]
    public function __invoke(
        #[MapEntity(expr: 'repository.getProjectWithAwards(id)')]
        Project $project
    ): Response {
        return $this->render('project/project_awards.html.twig', [
            'project' => $project
        ]);
    }
}
