<?php

namespace App\Controller;

use App\Dto\NewProjectAuthor;
use App\Entity\ProjectAuthor;
use App\Entity\User;
use App\Form\ProjectAuthorType;
use App\Form\ProjectAuthorUserType;
use App\Security\Voter\ProjectAuthorVoter;
use App\Service\Auth\AuthService;
use App\Service\Mail\UserMailerService;
use App\Service\User\UserService;
use ContainerGJsL5Pb\App_KernelDevDebugContainer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ProjectAuthorController extends AbstractController
{
    public function __construct(
        private readonly UserMailerService $userMailer,
        private readonly UserService $userService,
        private readonly AuthService $authService,
        private readonly EntityManagerInterface $entityManager,
    ) {}

    #[Route('/project-author/signup', name: 'app_project_author_signup')]
    public function signup(Request $request): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_index');
        }

        $projectAuthor = new ProjectAuthor();
        $form = $this->createForm(ProjectAuthorType::class, $projectAuthor);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $projectAuthor->getUserEntity();
            $password = $this->userService->generatePassword($user);

            $this->entityManager->persist($projectAuthor);
            $this->entityManager->flush();

            $loginLink = $this->authService->getLoginLink($user);

            $this->userMailer->sendToNewUser(new NewProjectAuthor(
                user: $user,
                password: $password,
                loginLink: $loginLink,
            ));

            return $this->render('project_author/success.html.twig');
        }

        return $this->render('project_author/signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // TODO: COMPLETE THIS SHEESH
    #[Route('/project-author/create', name: 'app_project_author_create')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
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
