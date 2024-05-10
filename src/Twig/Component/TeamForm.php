<?php

declare(strict_types=1);

namespace App\Twig\Component;

use App\Feature\Account\Dto\TeamData;
use App\Feature\Account\Form\TeamType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class TeamForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    public ?TeamData $data = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(TeamType::class, $this->data);
    }
}
