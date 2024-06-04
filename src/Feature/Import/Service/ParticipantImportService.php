<?php

declare(strict_types=1);

namespace App\Feature\Import\Service;

use App\Entity\Project;
use App\Feature\Import\Enum\ImportStatusEnum;
use App\Feature\Import\Interface\ProjectImportFactoryInterface;
use App\Feature\Import\Interface\ProjectStorageInterface;
use App\Feature\Import\ValueObject\ParticipantImportReport;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class ParticipantImportService
{
    public function __construct(
        private ProjectStorageInterface $projectStorage,
        private ProjectImportFactoryInterface $importFactory,
        private EntityManagerInterface $entityManager,
        private SyncParticipantsImporter $importer,
    ) {}

    public function import(Project $project, UploadedFile $file): ParticipantImportReport
    {
        $fileInfo = $this->projectStorage->upload($project, $file);
        $import = $this->importFactory->makeParticipantsImport(
            $project,
            $fileInfo->getFileName(),
        );

        $import->setStatus(ImportStatusEnum::PROCESSING);

        $this->entityManager->persist($import);
        $this->entityManager->flush();

        $result = $this->importer->import($import);

        $import->setStatus(ImportStatusEnum::COMPLETE);
        $import->markAsProcessed();
        $this->entityManager->flush();

        return $result;
    }
}
