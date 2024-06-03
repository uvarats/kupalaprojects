<?php

declare(strict_types=1);

namespace App\Twig\Component;

use App\Entity\Project;
use App\Feature\Import\Service\ParticipantImportService;
use App\Feature\Import\ValueObject\ParticipantsProcessingResult;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class ProjectParticipantsImport extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp]
    public Project $project;

    #[LiveProp]
    public ?string $report = null;

    #[LiveProp]
    public array $uploadErrors = [];

    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly ParticipantImportService $importService,
    ) {}

    #[LiveAction]
    public function import(Request $request): void
    {
        $file = $request->files->get('import_file');

        $errors = $this->validateFile($file);
        if (!empty($errors)) {
            $this->uploadErrors = $errors;

            return;
        }

        $result = $this->importService->import($this->project, $file);

        $report = $this->buildReport($result);

        if (empty($report)) {
            $report = 'Успешно!';
        }

        $this->report = $report;
    }

    private function validateFile(?UploadedFile $file): array
    {
        $errors = $this->validator->validate($file, [
            new NotNull(message: 'file.not_found'),
            new File([
                'maxSize' => '5120k',
                'mimeTypes' => [
                    'application/csv',
                    'text/x-comma-separated-values',
                    'text/x-csv',
                    'text/csv',
                ]
            ]),
        ]);

        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[] = $error->getMessage();
        }

        return $errorMessages;
    }

    private function buildReport(ParticipantsProcessingResult $processingResult): string
    {
        $report = '';

        $rejected = $processingResult->getRejectedParticipants();
        foreach ($rejected as $rejectedParticipant) {
            $report .= "<p>Участник {$rejectedParticipant->getDisplayString()} не был импортирован, так как был отклонён в процессе модерации</p>";
        }

        return $report;
    }
}
