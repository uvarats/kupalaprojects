<?php

declare(strict_types=1);

namespace App\Trait;

/**
 * Maybe replace this by ValueObject?
 */
trait NameTrait
{
    abstract public function getLastName(): string;

    abstract public function getFirstName(): string;

    abstract public function getMiddleName(): ?string;
}
