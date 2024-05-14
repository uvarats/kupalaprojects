<?php

declare(strict_types=1);

namespace App\Twig\Runtime;

use App\Enum\AcceptanceEnum;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\RuntimeExtensionInterface;

final readonly class AcceptanceExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(private TranslatorInterface $translator) {}

    public function toHtml(AcceptanceEnum $acceptanceEnum): string
    {
        $translation = $this->getTranslation($acceptanceEnum);

        return match($acceptanceEnum) {
            AcceptanceEnum::NO_DECISION => "<span>{$translation}</span>",
            AcceptanceEnum::APPROVED => "<span class='text-success'>{$translation}</span>",
            AcceptanceEnum::REJECTED => "<span class='text-danger'>{$translation}</span>",
        };
    }

    private function getTranslation(AcceptanceEnum $acceptanceEnum): string
    {
        return match($acceptanceEnum) {
            AcceptanceEnum::NO_DECISION => $this->translator->trans('general.acceptance.no_decision'),
            AcceptanceEnum::APPROVED => $this->translator->trans('general.acceptance.approved'),
            AcceptanceEnum::REJECTED => $this->translator->trans('general.acceptance.rejected'),
        };
    }
}
