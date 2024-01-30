<?php

declare(strict_types=1);

namespace App\Dto;

final readonly class FestivalDates
{
    public function __construct(
        public string $festivalId,
        public string $startsAt,
        public string $endsAt,
    ) {
    }

}
