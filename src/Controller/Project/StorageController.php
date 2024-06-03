<?php

declare(strict_types=1);

namespace App\Controller\Project;

use App\Entity\Project;
use App\Feature\Project\Security\ProjectVoter;
use League\Flysystem\FilesystemOperator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/project/{id}/storage/{filePath}', name: 'app_project_storage_download', requirements: ['filePath' => '.+'])]
final class StorageController extends AbstractController
{
    public function __construct(
        private readonly FilesystemOperator $projectStorage,
    ) {}

    public function __invoke(Project $project, string $filePath): StreamedResponse|Response
    {
        $this->denyAccessUnlessGranted(ProjectVoter::IS_PROJECT_OWNER, $project);

        $completePath = $project->filePath($filePath);

        if (!$this->projectStorage->fileExists($completePath)) {
            return new Response(status: 404);
        }

        $response = new StreamedResponse(function () use ($completePath) {
            $outputStream = fopen('php://output', 'wb');
            $file = $this->projectStorage->readStream($completePath);

            stream_copy_to_stream($file, $outputStream);
        });

        $pathParts = array_filter(explode('/', $filePath));
        $lastKey = array_key_last($pathParts);
        $fileName = $pathParts[$lastKey];

        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $fileName,
        );

        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }
}
