<?php

declare(strict_types=1);

namespace App\Controller\Project;

use App\Entity\User;
use App\Repository\ProjectRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class UserListingController extends AbstractController
{
    #[Route('/personal/projects/{page}', name: 'app_projects_personal')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function __invoke(
        #[CurrentUser] User $user,
        ProjectRepository $projectRepository,
        int $page = 1,
    ): Response {
        $query = $projectRepository->getUserProjectsQuery($user);

        $pager = new Pagerfanta(
            new QueryAdapter($query),
        );
        $pager->setMaxPerPage(50)
            ->setCurrentPage($page);

        return $this->render('project/my_projects.html.twig', [
            'projects' => $pager,
        ]);
    }
}
