<?php

namespace App\Controller;

use App\Dto\NewProjectAuthor;
use App\Entity\ProjectAuthor;
use App\Form\ProjectAuthorType;
use App\Service\Auth\AuthService;
use App\Service\Mail\UserMailerService;
use App\Service\User\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ProjectAuthorController extends AbstractController
{
    public function __construct(
        private readonly UserMailerService $userMailer,
        private readonly UserService $userService,
        private readonly AuthService $authService,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

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
}
