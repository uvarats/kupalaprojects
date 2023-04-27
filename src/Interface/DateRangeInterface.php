<?php

declare(strict_types=1);

namespace App\Interface;

use DateTimeImmutable;

interface DateRangeInterface
{
    public function getStartsAt(): ?DateTimeImmutable;
    public function setStartsAt(DateTimeImmutable $startsAt): DateRangeInterface;
    public function getEndsAt(): ?DateTimeImmutable;
    public function setEndsAt(DateTimeImmutable $endsAt): DateRangeInterface;
}
