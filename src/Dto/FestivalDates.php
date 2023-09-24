<?php

declare(strict_types=1);

namespace App\Dto;

use Uvarats\Dto\Data;

final class FestivalDates extends Data
{
    public function __construct(
        public string $festivalId,
        public string $startsAt,
        public string $endsAt,
    ) {}

}
