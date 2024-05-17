<?php

declare(strict_types=1);

namespace App\Feature\Team\Dto;

use App\Feature\Team\Validator\NoPendingInvites;
use Symfony\Component\Validator\Constraints as Assert;

final class InviteData
{
    #[Assert\Sequentially([
        new Assert\All(constraints: [
            new Assert\NotBlank(),
            new Assert\Email(),
        ]),
        new NoPendingInvites(),
    ])]
    private array $emails = [];

    public function getEmails(): array
    {
        return $this->emails;
    }

    public function setEmails(array $emails): void
    {
        $this->emails = $emails;
    }


}
