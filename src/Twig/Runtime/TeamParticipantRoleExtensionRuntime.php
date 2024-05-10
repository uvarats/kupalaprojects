<?php

declare(strict_types=1);

namespace App\Twig\Runtime;

use App\Enum\TeamParticipantRoleEnum;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\RuntimeExtensionInterface;

final readonly class TeamParticipantRoleExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private TranslatorInterface $translator,
    ) {}

    public function toHtml(TeamParticipantRoleEnum $roleEnum): string
    {
        $translation = $this->translateRole($roleEnum);

        return match ($roleEnum) {
            TeamParticipantRoleEnum::CREATOR => "<span class='text-primary'>{$translation}</span>",
            TeamParticipantRoleEnum::GENERAL_PARTICIPANT => "<span class='text-success'>{$translation}</span>",
        };
    }

    private function translateRole(TeamParticipantRoleEnum $roleEnum): string
    {
        return match ($roleEnum) {
            TeamParticipantRoleEnum::CREATOR => $this->translator->trans('team.participant.role.creator'),
            TeamParticipantRoleEnum::GENERAL_PARTICIPANT => $this->translator->trans('team.participant.role.general_participant'),
        };
    }
}
