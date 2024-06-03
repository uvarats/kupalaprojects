<?php

declare(strict_types=1);

namespace App\Feature\Import\ValueObject;

use App\Feature\Import\Enum\RowImportResultEnum;

final readonly class ParticipantImportReport
{
    public function __construct(
        private int $rowNumber,
        private RowImportResultEnum $result,
        private string $report,
    ) {}

    public function getRowNumber(): int
    {
        return $this->rowNumber;
    }

    public function getResult(): RowImportResultEnum
    {
        return $this->result;
    }

    public function getReport(): string
    {
        return $this->report;
    }
}
