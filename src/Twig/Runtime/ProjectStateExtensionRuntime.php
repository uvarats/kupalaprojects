<?php

declare(strict_types=1);

namespace App\Twig\Runtime;

use App\Enum\ProjectStateEnum;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\RuntimeExtensionInterface;

final readonly class ProjectStateExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(private TranslatorInterface $translator) {}

    public function makeState(string $state): string
    {
        $enum = ProjectStateEnum::from($state);
        return $this->getElement($enum);
    }

    private function getElement(ProjectStateEnum $projectStateEnum): string
    {
        $label = $this->getLabel($projectStateEnum);
        return match ($projectStateEnum) {
            ProjectStateEnum::UNDER_MODERATION => "<span class='text-warning'>{$label}</span>",
            ProjectStateEnum::APPROVED => "<span class='text-success'>{$label}</span>",
            ProjectStateEnum::REJECTED => "<span class='text-danger'>{$label}</span>",
            ProjectStateEnum::ENDED => "<span class='text-primary'>{$label}</span>",
            default => throw new \LogicException('Invalid project state'),
        };
    }

    private function getLabel(ProjectStateEnum $projectStateEnum): string
    {
        return match ($projectStateEnum) {
            ProjectStateEnum::UNDER_MODERATION => $this->translator->trans('project.state.under_moderation'),
            ProjectStateEnum::APPROVED => $this->translator->trans('project.state.approved'),
            ProjectStateEnum::REJECTED => $this->translator->trans('project.state.rejected'),
            ProjectStateEnum::ENDED => $this->translator->trans('project.state.ended'),
            default => throw new \LogicException('Invalid project state'),
        };
    }
}
