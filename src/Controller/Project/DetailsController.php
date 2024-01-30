<?php

declare(strict_types=1);

namespace App\Controller\Project;

use App\Entity\Project;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DetailsController extends AbstractController
{
    #[Route('/project/{id}', name: 'app_project_details')]
    public function __invoke(
        #[MapEntity(expr: 'repository.eagerLoad(id)')]
        Project $project
    ): Response {
        return $this->render('project/details.html.twig', [
            'project' => $project,
        ]);
    }
}
