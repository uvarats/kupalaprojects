<?php

declare(strict_types=1);

namespace App\Feature\Import\ValueObject;

use App\Feature\Import\Enum\ImportErrorReasonEnum;

final readonly class ParticipantImportError
{
    public function __construct(
        private int $rowNumber,
        private ImportErrorReasonEnum $errorReason,
        private string $message,
    ) {}

    public function getRowNumber(): int
    {
        return $this->rowNumber;
    }

    public function getErrorReason(): ImportErrorReasonEnum
    {
        return $this->errorReason;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
