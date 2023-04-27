<?php

namespace App\Controller;

use App\Entity\ProjectAuthor;
use App\Form\ProjectAuthorType;
use App\Service\User\UserMailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ProjectAuthorController extends AbstractController
{
    public function __construct(
        private readonly UserMailerService $userMailer,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/project-author/signup', name: 'app_project_author_signup')]
    public function signup(): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_index');
        }

        $projectAuthor = new ProjectAuthor();
        $form = $this->createForm(ProjectAuthorType::class, $projectAuthor);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($projectAuthor);
            $this->entityManager->flush();

            $user = $projectAuthor->getUserEntity();
            dd($user);
        }

        return $this->render('project_author/signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
