<?php

declare(strict_types=1);

namespace App\Repository\Interface;

use App\Collection\FestivalCollection;

interface FestivalRepositoryInterface
{
    public function findActive(): FestivalCollection;
}
