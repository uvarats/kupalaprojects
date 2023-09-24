<?php

declare(strict_types=1);

namespace App\Collection;

use App\Dto\FestivalDates;
use IteratorAggregate;
use Ramsey\Collection\AbstractCollection;

/**
 * @extends AbstractCollection<FestivalDates>
 *
 * @implements IteratorAggregate<FestivalDates>
 */
final class FestivalDatesCollection extends AbstractCollection
{

    public function getType(): string
    {
        return FestivalDates::class;
    }
}
