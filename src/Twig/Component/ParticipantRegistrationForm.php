<?php

declare(strict_types=1);

namespace App\Twig\Component;

use App\Dto\Participant\ParticipantData;
use App\Form\Project\ParticipantDataType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class ParticipantRegistrationForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ?ParticipantData $initialFormData = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(ParticipantDataType::class, $this->initialFormData);
    }
}
