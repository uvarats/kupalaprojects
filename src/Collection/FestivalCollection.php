<?php

declare(strict_types=1);

namespace App\Collection;

use App\Entity\Festival;

/**
 * @extends Collection<Festival>
 */
final class FestivalCollection extends Collection
{
    public function getType(): string
    {
        return Festival::class;
    }
}
