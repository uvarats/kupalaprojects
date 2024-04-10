<?php

declare(strict_types=1);

namespace App\Twig\Component;

use App\Dto\Form\Participant\TeamData;
use App\Form\Project\TeamDataType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class TeamRegistrationForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp()]
    public ?TeamData $initialFormData = null;

    protected function instantiateForm(): FormInterface
    {
        try {
            return $this->createForm(TeamDataType::class, $this->initialFormData);
        } catch (\Throwable $e) {
            dd($e, $this->initialFormData);
        }
    }

    #[LiveAction]
    public function addParticipant(): void
    {
        $this->formValues['participants'][] = [];
    }

    #[LiveAction]
    public function removeParticipant(#[LiveArg] int $index): void
    {
        unset($this->formValues['participants'][$index]);
    }
}
