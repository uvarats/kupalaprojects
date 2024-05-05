<?php

declare(strict_types=1);

namespace App\Feature\Core\Collection;

use App\Collection\Collection;
use App\Feature\Core\ValueObject\Email;

/**
 * @extends Collection<Email>
 */
final class EmailCollection extends Collection implements \JsonSerializable
{
    #[\Override]
    public function getType(): string
    {
        return Email::class;
    }

    #[\Override]
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
