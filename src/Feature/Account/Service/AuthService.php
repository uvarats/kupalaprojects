<?php

declare(strict_types=1);

namespace App\Feature\Account\Service;

use App\Entity\User;
use App\Feature\Account\Dto\NewUserMailData;
use App\Feature\Account\Dto\UserSignUpRequest;
use App\Feature\Account\ValueObject\Password;
use App\Service\Mail\UserMailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;

final readonly class AuthService
{
    public function __construct(
        private LoginLinkHandlerInterface $loginLinkHandler,
        private PasswordGenerator $generator,
        private EntityManagerInterface $entityManager,
        private UserMailerService $mailer,
    ) {}

    public function getLoginLink(User $user): string
    {
        // link forwards to project create page
        $loginLinkDetails = $this->loginLinkHandler->createLoginLink($user);

        return $loginLinkDetails->getUrl();
    }

    public function signUp(UserSignUpRequest $request): User
    {
        $name = $request->getName();
        $email = $request->getEmail();
        $password = $request->getPassword();

        $user = User::create($name, $email, $password);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->sendMail($user, $password);

        return $user;
    }

    private function sendMail(User $user, Password $password): void
    {
        $loginLink = $this->getLoginLink($user);
        $plainPassword = $password->getPlainPassword();

        $mailData = new NewUserMailData($user, $plainPassword, $loginLink);
        $this->mailer->sendToNewUser($mailData);
    }
}
