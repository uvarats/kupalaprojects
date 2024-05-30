<?php

declare(strict_types=1);

namespace App\Feature\Import\Interface;

use App\Entity\Import;

interface ImporterInterface
{
    public function handle(Import $import): void;
}
