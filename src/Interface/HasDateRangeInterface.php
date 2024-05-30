<?php

declare(strict_types=1);

namespace App\Interface;

use DateTimeImmutable;

interface HasDateRangeInterface
{
    public function getStartsAt(): ?DateTimeImmutable;
    public function getEndsAt(): ?DateTimeImmutable;
}
