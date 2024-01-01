<?php

declare(strict_types=1);

namespace App\Twig\Component;

use App\Entity\Team;
use App\Form\Project\TeamType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class TeamRegistrationForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp()]
    public ?Team $initialFormData = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(TeamType::class, $this->initialFormData);
    }
}
