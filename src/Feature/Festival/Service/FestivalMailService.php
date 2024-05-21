<?php

declare(strict_types=1);

namespace App\Feature\Festival\Service;

use App\Entity\FestivalMail;
use App\Feature\Festival\Dto\CreateFestivalMail;
use App\Message\SendFestivalMail;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class FestivalMailService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MessageBusInterface $bus,
    ) {}

    /**
     * @deprecated
     */
    public function processMail(FestivalMail $mail): void
    {
        $mail->setSentAt(new DateTimeImmutable());

        $this->entityManager->persist($mail);
        $this->entityManager->flush();

        $message = SendFestivalMail::fromMail($mail);
        $this->bus->dispatch($message);
    }

    public function process(CreateFestivalMail $request): void
    {

    }
}
