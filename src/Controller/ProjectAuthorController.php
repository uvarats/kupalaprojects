<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\ProjectAuthor;
use App\Entity\User;
use App\Feature\Account\Form\ProjectAuthorUserType;
use App\Security\Voter\ProjectAuthorVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class ProjectAuthorController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    #[Route('/project-author/create', name: 'app_project_author_create')]
    public function createProjectAuthorForExistingUser(
        #[CurrentUser]
        User $user,
        Request $request,
    ): Response {
        if ($this->isGranted(ProjectAuthorVoter::IS_PROJECT_AUTHOR)) {
            return $this->redirectToRoute('app_index');
        }

        $projectAuthor = new ProjectAuthor();
        $projectAuthor->setUserEntity($user);

        $form = $this->createForm(ProjectAuthorUserType::class, $projectAuthor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($projectAuthor);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_projects_personal');
        }

        return $this->render('project_author/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/project-author/update', name: 'app_project_author_update')]
    public function projectAuthorUpdate(
        #[CurrentUser]
        User $user,
        Request $request,
    ): Response {
        if (!$this->isGranted(ProjectAuthorVoter::IS_PROJECT_AUTHOR)) {
            return $this->redirectToRoute('app_project_author_create');
        }

        $projectAuthor = $user->getProjectAuthor();
        $form = $this->createForm(ProjectAuthorUserType::class, $projectAuthor);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Успешно сохранено');
        }

        return $this->render('project_author/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
