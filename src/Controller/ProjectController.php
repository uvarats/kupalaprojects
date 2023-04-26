<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\User;
use App\Form\ProjectType;
use App\Repository\ProjectAuthorRepository;
use App\Repository\UserRepository;
use App\Service\Project\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ProjectController extends AbstractController
{
    public function __construct(
        private readonly ProjectService $projectService,
    )
    {
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function userOwnedProjects()
    {
    }

    #[Route('/projects/create', name: 'app_projects_create')]
    public function createProject(
        #[CurrentUser] ?User $user,
        Request $request,
        UserRepository $userRepository,
        ProjectAuthorRepository $projectAuthorRepository,
    ) {
        $project = $this->projectService->createProject($user);

        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dd($form);
        }

        return $this->render('project/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
