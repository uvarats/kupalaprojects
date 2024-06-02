<?php

declare(strict_types=1);

namespace App\Feature\Account\Service;

use App\Entity\User;
use App\Feature\Account\Dto\NewUserMailData;
use App\Feature\Account\Dto\UserSignUpRequest;
use App\Feature\Account\ValueObject\Password;
use App\Feature\Participant\Repository\ParticipantRepository;
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
        private ParticipantRepository $participantRepository,
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

        // linking anonymous participant by email if exists
        $participant = $this->participantRepository->findOneBy(['email' => $email]);
        if ($participant !== null) {
            $participant->setAccount($user);

            $this->entityManager->flush();
        }

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
