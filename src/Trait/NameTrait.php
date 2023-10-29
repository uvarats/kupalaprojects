<?php

declare(strict_types=1);

namespace App\Trait;

/**
 * Maybe replace this by ValueObject?
 */
trait NameTrait
{
    public function getFullName(): string
    {
        $lastName = $this->getLastName();
        $firstAndMiddleName = $this->getFirstAndMiddleName();

        return $lastName . ' ' . $firstAndMiddleName;
    }

    public function getFirstAndMiddleName(): string
    {
        $firstName = $this->getFirstName();
        $middleName = $this->getMiddleName();

        $string = "{$firstName}";

        if ($middleName !== null) {
            $string .= " {$middleName}";
        }

        return $string;
    }

    abstract public function getLastName(): ?string;

    abstract public function getFirstName(): ?string;

    abstract public function getMiddleName(): ?string;
}
