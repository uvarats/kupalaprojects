<?php

declare(strict_types=1);

namespace App\Controller\Account;

use App\Entity\User;
use App\Feature\Account\Dto\UserSignUpRequest;
use App\Feature\Account\Dto\UserData;
use App\Feature\Account\Form\UserDataType;
use App\Feature\Account\Service\AuthService;
use App\Feature\Account\Service\PasswordGenerator;
use App\ValueObject\Email;
use App\ValueObject\PersonName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

#[Route('/signup', name: 'app_signup')]
final class SignUp extends AbstractController
{
    public function __construct(
        private readonly PasswordGenerator $passwordGenerator,
        private readonly AuthService $authService,
    ) {}

    public function __invoke(Request $request): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_index');
        }

        $userData = new UserData();
        $form = $this->createForm(UserDataType::class, $userData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $signUpRequest = $this->composeSignUpRequest($userData);

            $user = $this->authService->signUp($signUpRequest);

            if ($request->getPreferredFormat() === TurboBundle::STREAM_FORMAT) {
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
                return $this->renderBlock('account/signup/index.html.twig', 'success_stream', ['user' => $user]);
            }

            return $this->render('account/signup/success.html.twig', [
                'user' => $user,
            ]);
        }

        return $this->render('account/signup/index.html.twig', [
            'form' => $form->createView(),
            'data' => $userData,
        ]);
    }

    private function composeSignUpRequest(UserData $data): UserSignUpRequest
    {
        $name = PersonName::make(
            lastName: $data->getLastName(),
            firstName: $data->getFirstName(),
            middleName: $data->getMiddleName(),
        );

        $email = Email::fromString(
            email: $data->getEmail(),
        );

        $password = $this->passwordGenerator->generate(new User());

        return UserSignUpRequest::make(
            name: $name,
            email: $email,
            password: $password,
        );
    }
}
