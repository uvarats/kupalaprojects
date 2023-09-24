<?php

declare(strict_types=1);

namespace App\Controller\Project;

use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ListingController extends AbstractController
{
    public function __construct(
        private readonly PaginatedFinderInterface $finder,
    ) {}

    #[Route('/projects/{page}', name: 'app_projects')]
    public function __invoke(Request $request, int $page = 1): Response
    {
        $queryString = $request->query->get('query');

        $pager = $this->finder->findPaginated($queryString);

        $pager->setMaxPerPage(50)
            ->setCurrentPage($page);


        return $this->render('project/index.html.twig', [
            'projects' => $pager,
        ]);
    }
}
