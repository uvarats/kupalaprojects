<?php

declare(strict_types=1);

namespace App\Feature\Import\Service;

use App\Entity\Project;
use App\Feature\Core\ValueObject\FileInfoData;
use App\Feature\Import\Interface\ProjectStorageInterface;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

final readonly class ProjectStorage implements ProjectStorageInterface
{
    public function __construct(
        private string $targetDirectory,
        private SluggerInterface $slugger,
    ) {}

    public function upload(Project $project, UploadedFile $file): FileInfoData
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . uniqid() . '.' . $file->guessExtension();

        $directory = $this->getProjectDirectory($project);

        $file->move($directory, $fileName);

        return new FileInfoData(
            fileName: $fileName,
            directory: $directory,
        );
    }

    public function getProjectDirectory(Project $project): string
    {
        $hash = md5($project->getId()->toString());

        return $this->targetDirectory . DIRECTORY_SEPARATOR . $hash;
    }
}
