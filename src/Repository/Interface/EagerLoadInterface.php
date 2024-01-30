<?php

declare(strict_types=1);

namespace App\Repository\Interface;

interface EagerLoadInterface
{
    public function eagerLoad(int|string $id): ?object;
}
