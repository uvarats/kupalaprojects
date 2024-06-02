<?php

declare(strict_types=1);

namespace App\Feature\Import\Service;

use App\Entity\Participant;
use App\Entity\Project;
use App\Entity\ProjectImport;
use App\Feature\Core\Validation\FullName;
use App\Feature\Import\Collection\ParticipantImportErrorCollection;
use App\Feature\Import\Collection\ParticipantRowCollection;
use App\Feature\Import\Dto\ParticipantRow;
use App\Feature\Import\Enum\ImportErrorReasonEnum;
use App\Feature\Import\ValueObject\ParticipantImportError;
use App\Feature\Participant\Collection\ParticipantCollection;
use App\Feature\Participant\Repository\ParticipantRepository;
use App\Feature\Project\Repository\ProjectParticipantRepository;
use App\ValueObject\PersonName;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class SyncParticipantsImporter
{
    public function __construct(
        private FilesystemOperator $projectStorage,
        private EntityManagerInterface $entityManager,
        private ValidatorInterface $validator,
        private TranslatorInterface $translator,
        private ParticipantRepository $participantRepository,
        private ProjectParticipantRepository $projectParticipantRepository,
    ) {}

    public function import(ProjectImport $import) {
        $filePath = $import->getFilePath();

        if (!$this->projectStorage->fileExists($filePath)) {
            throw new \LogicException('What?');
        }

        $fileStream = $this->projectStorage->readStream($filePath);

        // skipping first line
        fgetcsv($fileStream);

        $errors = new ParticipantImportErrorCollection();
        $readRows = 0;
        $rows = new ParticipantRowCollection();
        while (($data = fgetcsv($fileStream)) !== false) {
            $readRows++;

            $error = $this->validateRow($readRows + 1, $data);

            if ($error !== null) {
                $errors[] = $error;

                continue;
            }

            $rowData = new ParticipantRow(
                rowNumber: $readRows + 1,
                fullName: $data[0],
                email: $data[1],
                educationEstablishment: $data[2],
            );

            $email = $rowData->getEmail();
            $rows[$email] = $rowData;
        }

        $this->processChunk($rows, $import->getProject());
    }

    private function validateRow(int $rowNumber, array $data): ?ParticipantImportError
    {
        if (count($data) !== 3) {
            return new ParticipantImportError(
                rowNumber: $rowNumber,
                errorReason: ImportErrorReasonEnum::COLUMN_COUNT_MISMATCH,
                message: $this->translator->trans('participant.import.column_mismatch'),
            );
        }

        $data = array_combine(['fullName', 'email', 'educationEstablishment'], $data);

        $violations = $this->validateRawData($data);

        if ($violations->count() === 0) {
            return null;
        }

        $message = '';
        foreach ($violations as $violation) {
            $message .= $violation->getMessage() . ' ';
        }

        return new ParticipantImportError(
            rowNumber: $rowNumber,
            errorReason: ImportErrorReasonEnum::INVALID_DATA,
            message: $message,
        );
    }

    private function validateRawData(array $data): ConstraintViolationListInterface
    {
        $constraint = new Assert\Collection([
            'fullName' => [
                new Assert\NotBlank(),
                new FullName(),
            ],
            'email' => [
                new Assert\NotBlank(),
                new Assert\Email(),
            ],
            'educationEstablishment' => [new Assert\NotBlank()],
        ]);

        return $this->validator->validate($data, $constraint);
    }

    private function processChunk(ParticipantRowCollection $rows, Project $project) {
        $emails = array_keys($rows->toArray());

        $existingParticipants = $this->makeExistingParticipantsMap($emails);
        $alreadyParticipating = $this->makeAlreadyParticipatingMap($project, $existingParticipants);
        $existingNotParticipating = $existingParticipants->diff($alreadyParticipating);

        $newParticipants = new ParticipantCollection();

        foreach ($rows as $email => $row) {
            $participant = $existingParticipants[$email] ?? null;

            if ($participant !== null) {
                continue;
            }

            $participant = $this->createParticipant($row);
            $newParticipants[] = $participant;

            $this->entityManager->persist($participant);
        }

        dd([
            'existing' => $existingParticipants,
            'participating' => $alreadyParticipating,
            'existing_but_not_participating' => $existingNotParticipating,
            'new' => $newParticipants,
        ]);

        //$this->entityManager->flush();
    }

    private function makeExistingParticipantsMap(array $emails): ParticipantCollection
    {
        $existingParticipants = $this->participantRepository->findByEmails($emails);

        $participantsMap = [];
        foreach ($existingParticipants as $participant) {
            $email = $participant->getEmail();
            $participantsMap[$email] = $participant;
        }

        return new ParticipantCollection($participantsMap);
    }

    private function makeAlreadyParticipatingMap(Project $project, ParticipantCollection $participants): ParticipantCollection
    {
        $alreadyParticipating = $this->projectParticipantRepository->findAlreadyParticipating($project, $participants);

        $map = [];
        foreach ($alreadyParticipating as $projectParticipant) {
            $participant = $projectParticipant->getParticipant();
            $email = $participant->getEmail();

            $map[$email] = $participant;
        }

        return new ParticipantCollection($map);
    }

    private function createParticipant(ParticipantRow $row): Participant
    {
        $nameParts = explode(' ', $row->getFullName());
        $nameParts = array_filter($nameParts);

        return Participant::make(
            name: PersonName::make(
                lastName: $nameParts[0],
                firstName: $nameParts[1],
                middleName: $nameParts[2] ?? null,
            ),
            educationEstablishment: $row->getEducationEstablishment(),
            email: $row->getEmail(),
        );
    }
}
