<?php

declare(strict_types=1);

namespace App\Feature\Account\Service;

use App\Entity\Participant;
use App\Feature\Account\Dto\CreateAccountParticipantRequest;
use App\ValueObject\PersonName;
use Doctrine\ORM\EntityManagerInterface;

final readonly class AccountParticipantService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    public function create(CreateAccountParticipantRequest $request): Participant
    {
        $account = $request->getAccount();

        $name = $account->getPersonName();

        $establishment = $request->getEducationEstablishment();
        $email = $account->getEmail();

        $participant = Participant::make($name, $establishment, $email);
        $participant->setAccount($account);

        $this->entityManager->persist($participant);
        $this->entityManager->flush();

        return $participant;
    }
}
