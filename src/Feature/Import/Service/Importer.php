<?php

declare(strict_types=1);

namespace App\Feature\Import\Service;

use App\Entity\Import;
use App\Feature\Import\Interface\ImportServiceInterface;
use Psr\Container\ContainerInterface;

final readonly class Importer implements ImportServiceInterface
{
    public function __construct(
        private ContainerInterface $importLocator,
    ) {}

    #[\Override]
    public function import(Import $import): void
    {
        dd($this->importLocator);
    }
}
