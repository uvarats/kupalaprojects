<?php

declare(strict_types=1);

namespace App\Controller\Project;

use App\Feature\Project\Security\ProjectAuthorVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/projects/create', name: 'app_projects_create')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class CreateController extends AbstractController
{
    public function __invoke(
        Request $request,
    ): Response {
        if (!$this->isGranted(ProjectAuthorVoter::HAS_PROJECT_AUTHOR_DATA)) {
            return $this->redirectToRoute('app_project_author_create');
        }

        return $this->render('project/create.html.twig');
    }
}
