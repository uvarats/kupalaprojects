<?php

declare(strict_types=1);

namespace App\Twig\Component;

use App\Feature\Account\Dto\UserData;
use App\Feature\Account\Form\UserDataType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class AccountSignUpForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ?UserData $data = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(UserDataType::class, $this->data);
    }
}
