<?php

declare(strict_types=1);

namespace App\Controller\Account;

use App\Entity\User;
use App\Feature\Account\Dto\AccountParticipantData;
use App\Feature\Account\Dto\CreateAccountParticipantRequest;
use App\Feature\Account\Form\AccountParticipantType;
use App\Feature\Account\Service\AccountParticipantService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/account/participant/create', name: 'app_account_participant_create')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class CreateParticipant extends AbstractController
{
    public function __construct(
        private readonly AccountParticipantService $accountParticipantService,
    ) {}

    public function __invoke(Request $request, #[CurrentUser] User $user): Response
    {
        $data = new AccountParticipantData();
        $form = $this->createForm(AccountParticipantType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createRequest = CreateAccountParticipantRequest::forUser($user, $data->getEducationEstablishment());

            $participant = $this->accountParticipantService->create($createRequest);

            return $this->redirectToRoute('app_account_participant_view');
        }

        return $this->render('account/participant/create.html.twig', [
            'form' => $form->createView(),
            'data' => $data,
        ]);
    }
}
