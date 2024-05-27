<?php

declare(strict_types=1);

namespace App\Controller\Project;

use App\Dto\ProjectQuery;
use App\Form\Project\ProjectQueryType;
use App\Service\Project\ProjectSearchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ListingController extends AbstractController
{
    public function __construct(
        private readonly ProjectSearchService $projectFilter,
    ) {}

    #[Route('/projects/{page}', name: 'app_projects')]
    public function __invoke(Request $request, int $page = 1): Response
    {
        $query = ProjectQuery::empty();

        $form = $this->createForm(ProjectQueryType::class, $query);

        $form->handleRequest($request);

        $pager = $this->projectFilter->findPaginated(
            query: $query,
            maxPerPage: 50,
            currentPage: $page,
        );

        return $this->render('project/index.html.twig', [
            'projects' => $pager,
            'search' => $form->createView(),
        ]);
    }
}
