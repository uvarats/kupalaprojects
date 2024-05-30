<?php

declare(strict_types=1);

namespace App\Feature\Import\Service;

use App\Entity\Import;
use App\Feature\Import\Interface\ImporterInterface;

class ParticipantImporter implements ImporterInterface
{
    #[\Override]
    public function handle(Import $import): void
    {
        // TODO: Implement handle() method.
    }
}
