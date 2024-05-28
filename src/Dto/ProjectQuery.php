<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\EducationSubGroup;
use App\Entity\Festival;
use App\Entity\ProjectSubject;

final class ProjectQuery
{
    public function __construct(
        private ?string $query,
        private ?Festival $festival = null,
        /** @var ProjectSubject[] $subjects */
        private array $subjects = [],
        /** @var EducationSubGroup[] $orientedOn */
        private array $orientedOn = [],
        private ?\DateTimeImmutable $dateFrom = null,
        private ?\DateTimeImmutable $dateTo = null,
    ) {}

    public static function empty(): ProjectQuery
    {
        return new self(
            query: null,
        );
    }

    public function getQuery(): ?string
    {
        return $this->query;
    }

    public function setQuery(?string $query): void
    {
        $this->query = $query;
    }

    public function getFestival(): ?Festival
    {
        return $this->festival;
    }

    public function setFestival(?Festival $festival): void
    {
        $this->festival = $festival;
    }

    public function isEmptyQuery(): bool
    {
        $objectVars = get_object_vars($this);
        $filteredVars = array_filter($objectVars);

        return empty($filteredVars);
    }

    public function getDateFrom(): ?\DateTimeImmutable
    {
        return $this->dateFrom;
    }

    public function setDateFrom(?\DateTimeImmutable $dateFrom): void
    {
        $this->dateFrom = $dateFrom;
    }

    public function getDateTo(): ?\DateTimeImmutable
    {
        return $this->dateTo;
    }

    public function setDateTo(?\DateTimeImmutable $dateTo): void
    {
        $this->dateTo = $dateTo;
    }

    public function getSubjects(): array
    {
        return $this->subjects;
    }

    public function setSubjects(array $subjects): void
    {
        $this->subjects = $subjects;
    }

    public function getOrientedOn(): array
    {
        return $this->orientedOn;
    }

    public function setOrientedOn(array $orientedOn): void
    {
        $this->orientedOn = $orientedOn;
    }
}
