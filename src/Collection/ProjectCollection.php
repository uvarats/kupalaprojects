<?php

declare(strict_types=1);

namespace App\Collection;

use App\Entity\Project;
use Ramsey\Collection\AbstractCollection;

/**
 * @extends AbstractCollection<Project>
 *
 * @implements \IteratorAggregate<Project>
 *
 * @method Project offsetGet(mixed $offset)
 */
final class ProjectCollection extends AbstractCollection
{
    public function getType(): string
    {
        return Project::class;
    }
}
