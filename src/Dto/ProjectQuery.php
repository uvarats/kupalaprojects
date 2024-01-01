<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\Festival;

final class ProjectQuery
{
    public function __construct(
        private ?string $query,
        private ?Festival $festival = null,
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
        return $this->query === null && $this->festival === null;
    }
}
