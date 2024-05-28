<?php

declare(strict_types=1);

namespace App\Controller;

use App\Feature\Project\Collection\ProjectCollection;
use App\Feature\Project\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    public function __construct(
        private readonly ProjectRepository $projectRepository,
    ) {}

    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        $projects = $this->projectRepository->findActualProjects();

        return $this->render('index/index.html.twig', [
            'projects' => $projects,
        ]);
    }
}
