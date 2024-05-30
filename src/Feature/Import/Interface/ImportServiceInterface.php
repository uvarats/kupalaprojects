<?php

declare(strict_types=1);

namespace App\Feature\Import\Interface;

use App\Entity\Import;

interface ImportServiceInterface
{
    public function import(Import $import): void;
}
