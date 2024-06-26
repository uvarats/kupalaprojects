<?php

declare(strict_types=1);

namespace App\Twig\Component;

use App\Entity\Festival;
use App\Entity\FestivalMail;
use App\Entity\User;
use App\Feature\Festival\Dto\CreateFestivalMail;
use App\Feature\Festival\Dto\FestivalMailData;
use App\Feature\Festival\Service\FestivalMailService;
use App\Form\FestivalMailType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class FestivalMailForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ?FestivalMailData $data = null;

    #[LiveProp]
    public Festival $festival;
    public bool $isSent = false;
    public ?FestivalMail $mail = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(FestivalMailType::class, $this->data, ['festival' => $this->festival]);
    }

    #[LiveAction]
    public function submit(#[CurrentUser] User $user, FestivalMailService $mailService): void
    {
        $this->submitForm();

        $data = $this->getForm()->getData();
        $mailRequest = $this->composeRequest($data, $user);

        $this->mail = $mailService->process($mailRequest);
        $this->isSent = true;
    }

    private function composeRequest(FestivalMailData $mailData, User $user): CreateFestivalMail
    {
        return new CreateFestivalMail(
            festival: $this->festival,
            author: $user,
            subject: $mailData->getSubject(),
            content: $mailData->getContent(),
            recipients: array_unique($mailData->getRecipients()),
        );
    }
}
