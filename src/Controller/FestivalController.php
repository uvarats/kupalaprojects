<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Festival;
use App\Repository\FestivalRepository;
use App\Repository\ProjectRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FestivalController extends AbstractController
{
    public function __construct(
        private readonly ProjectRepository $projectRepository
    ) {}

    #[Route('/festivals/{page}', name: 'app_festivals')]
    public function index(FestivalRepository $festivalRepository, int $page = 1): Response
    {
        $query = $festivalRepository->createOrderedQuery();

        $pager = new Pagerfanta(
            new QueryAdapter($query)
        );

        $pager->setMaxPerPage(50)
            ->setCurrentPage($page);

        return $this->render('festival/index.html.twig', [
            'festivals' => $pager,
        ]);
    }

    #[Route('/festival/{id}/projects/{page}', name: 'app_festival_projects')]
    public function projects(
        Festival $festival,
        int $page = 1
    ): Response {
        $projectRepository = $this->projectRepository;
        $projectsQuery = $projectRepository->getFestivalProjectsQuery($festival);

        $pager = new Pagerfanta(
            new QueryAdapter($projectsQuery)
        );
        $pager->setMaxPerPage(50)
            ->setCurrentPage($page);

        return $this->render('festival/project.html.twig', [
            'festival' => $festival,
            'projects' => $pager,
        ]);
    }

}
