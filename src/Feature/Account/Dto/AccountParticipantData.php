<?php

declare(strict_types=1);

namespace App\Feature\Account\Dto;

use Symfony\Component\Validator\Constraints as Assert;

/*
 * When we're creating participant for an account, we can inherit name and email from account entity
 * Only field that we must fill is education establishment
 */
final class AccountParticipantData
{
    #[Assert\NotBlank]
    private string $educationEstablishment = '';

    public function getEducationEstablishment(): string
    {
        return $this->educationEstablishment;
    }

    public function setEducationEstablishment(string $educationEstablishment): void
    {
        $this->educationEstablishment = $educationEstablishment;
    }

}
