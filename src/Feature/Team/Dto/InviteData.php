<?php

declare(strict_types=1);

namespace App\Feature\Team\Dto;

use App\Feature\Core\Validator as CustomAssert;
use Symfony\Component\Validator\Constraints as Assert;

final class InviteData
{
    #[Assert\NotBlank]
    #[CustomAssert\StringWithEmails]
    private string $emails = '';

    public function getEmails(): string
    {
        return $this->emails;
    }

    public function setEmails(string $emails): void
    {
        $this->emails = $emails;
    }


}
