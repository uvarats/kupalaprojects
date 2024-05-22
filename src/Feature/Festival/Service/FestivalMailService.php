<?php

declare(strict_types=1);

namespace App\Feature\Festival\Service;

use App\Entity\FestivalMail;
use App\Feature\Festival\Dto\CreateFestivalMail;
use App\Message\SendFestivalMail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class FestivalMailService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MessageBusInterface $bus,
    ) {}

    public function process(CreateFestivalMail $request): FestivalMail
    {
        $festivalMail = FestivalMail::create(
            festival: $request->getFestival(),
            author: $request->getAuthor(),
            subject: $request->getSubject(),
            content: $request->getContent(),
            recipients: $request->getRecipients(),
            cc: $request->getCc(),
            bcc: $request->getBcc(),
        );

        $this->entityManager->persist($festivalMail);
        $this->entityManager->flush();

        $message = SendFestivalMail::fromMail($festivalMail);
        $this->bus->dispatch($message);

        return $festivalMail;
    }
}
