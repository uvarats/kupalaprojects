<?php

declare(strict_types=1);

namespace App\Collection;

use App\Entity\Participant;
use IteratorAggregate;
use Ramsey\Collection\AbstractCollection;

/**
 * @extends AbstractCollection<Participant>
 *
 * @implements IteratorAggregate<Participant>
 */
final class ParticipantCollection extends AbstractCollection
{
    public function getType(): string
    {
        return Participant::class;
    }
}
