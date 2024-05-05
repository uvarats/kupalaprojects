<?php

declare(strict_types=1);

namespace App\Twig\Runtime;

use App\Feature\Team\Enum\InviteStatusEnum;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\RuntimeExtensionInterface;
use Twig\Markup;

final readonly class InviteStatusExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private TranslatorInterface $translator,
    ) {}

    public function toHtml(InviteStatusEnum $status): Markup
    {
        $translation = $this->translate($status);

        $html = match ($status) {
            InviteStatusEnum::PENDING => "<span class='text-warning'>{$translation}</span>",
            InviteStatusEnum::ACCEPTED => "<span class='text-success'>{$translation}</span>",
            InviteStatusEnum::REJECTED => "<span class='text-danger'>{$translation}</span>",
            InviteStatusEnum::REVOKED => "<span class='text-info'>{$translation}</span>",
        };

        return new Markup($html, 'UTF-8');
    }

    private function translate(InviteStatusEnum $enum): string
    {
        $key = $this->getTranslationKey($enum);

        return $this->translator->trans($key);
    }

    private function getTranslationKey(InviteStatusEnum $enum): string {
        $baseKey = 'team.invite.status';
        $concreteKey = match ($enum) {
            InviteStatusEnum::PENDING => 'pending',
            InviteStatusEnum::ACCEPTED => 'accepted',
            InviteStatusEnum::REJECTED => 'rejected',
            InviteStatusEnum::REVOKED => 'revoked',
        };

        return $baseKey . '.' . $concreteKey;
    }
}
