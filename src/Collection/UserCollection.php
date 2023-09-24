<?php

declare(strict_types=1);

namespace App\Collection;

use App\Entity\User;
use IteratorAggregate;
use Ramsey\Collection\AbstractCollection;

/**
 * @extends AbstractCollection<User>
 *
 * @implements IteratorAggregate<User>
 */
final class UserCollection extends AbstractCollection
{
    public function getType(): string
    {
        return User::class;
    }
}
