<?php

declare(strict_types=1);

namespace App\Feature\Account\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class TeamData
{
    #[Assert\NotBlank]
    private string $name = '';

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

}
