<?php

declare(strict_types=1);

namespace App\Collection;

use App\Dto\FestivalDates;

/**
 * @extends Collection<FestivalDates>
 */
final class FestivalDatesCollection extends Collection
{
    public function getType(): string
    {
        return FestivalDates::class;
    }
}
