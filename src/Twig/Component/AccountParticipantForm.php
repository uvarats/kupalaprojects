<?php

declare(strict_types=1);

namespace App\Twig\Component;

use App\Dto\Form\Participant\ParticipantData;
use App\Feature\Account\Dto\AccountParticipantData;
use App\Feature\Account\Form\AccountParticipantType;
use App\Form\Project\ParticipantDataType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class AccountParticipantForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ?AccountParticipantData $initialFormData = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(AccountParticipantType::class, $this->initialFormData);
    }
}
