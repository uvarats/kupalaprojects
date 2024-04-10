<?php

declare(strict_types=1);

namespace App\Repository\Interface;

use App\Collection\FestivalCollection;
use App\Entity\Festival;
use App\ValueObject\Entity\FestivalId;

interface FestivalRepositoryInterface
{
    public function findActive(): FestivalCollection;
    public function findById(FestivalId $id): ?Festival;
}
