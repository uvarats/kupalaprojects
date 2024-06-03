<?php

declare(strict_types=1);

namespace App\Twig\Runtime;

use App\Feature\Import\Enum\ImportStatusEnum;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\RuntimeExtensionInterface;

final readonly class ImportStatusExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private TranslatorInterface $translator,
    ) {}

    public function translateStatus(ImportStatusEnum $value): string
    {
        return match($value) {
            ImportStatusEnum::NEW => $this->translator->trans('participant.import.status.new'),
            ImportStatusEnum::PROCESSING => $this->translator->trans('participant.import.status.processing'),
            ImportStatusEnum::COMPLETE => $this->translator->trans('participant.import.status.complete'),
            ImportStatusEnum::ERROR => $this->translator->trans('participant.import.status.error'),
        };
    }
}
