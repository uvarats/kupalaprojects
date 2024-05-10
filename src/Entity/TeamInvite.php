<?php

declare(strict_types=1);

namespace App\Entity;

use App\Feature\Team\Enum\InviteStatusEnum;
use App\Feature\Team\Repository\TeamInviteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TeamInviteRepository::class)]
class TeamInvite
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255, enumType: InviteStatusEnum::class)]
    private InviteStatusEnum $status = InviteStatusEnum::PENDING;
    #[ORM\Column]
    private \DateTimeImmutable $issuedAt;

    #[ORM\Column]
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        #[ORM\ManyToOne]
        #[ORM\JoinColumn(nullable: false)]
        private readonly Team $team,
        #[ORM\ManyToOne]
        #[ORM\JoinColumn(nullable: false)]
        private readonly Participant $issuer,
        #[ORM\ManyToOne]
        #[ORM\JoinColumn(nullable: false)]
        private readonly Participant $recipient,
    ) {
        $this->issuedAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getIssuer(): Participant
    {
        return $this->issuer;
    }

    public function getRecipient(): Participant
    {
        return $this->recipient;
    }

    public function isRecipient(Participant $participant): bool
    {
        return $this->recipient === $participant;
    }

    public function isIssuer(Participant $participant): bool
    {
        return $this->issuer === $participant;
    }

    public function getStatus(): InviteStatusEnum
    {
        return $this->status;
    }

    public function isPending(): bool
    {
        return $this->status === InviteStatusEnum::PENDING;
    }

    public function isAccepted(): bool
    {
        return $this->status === InviteStatusEnum::ACCEPTED;
    }

    public function isRejected(): bool
    {
        return $this->status === InviteStatusEnum::REJECTED;
    }

    public function isRevoked(): bool
    {
        return $this->status === InviteStatusEnum::REVOKED;
    }

    public function accept(): void
    {
        if ($this->status !== InviteStatusEnum::PENDING) {
            return;
        }

        $participant = $this->recipient;
        $this->team->addGeneralParticipant($participant);

        $this->changeStatus(InviteStatusEnum::ACCEPTED);
    }

    public function reject(): void
    {
        if ($this->status !== InviteStatusEnum::PENDING) {
            return;
        }

        $this->changeStatus(InviteStatusEnum::REJECTED);
    }

    public function revoke(): void
    {
        if ($this->status !== InviteStatusEnum::PENDING) {
            return;
        }

        $this->changeStatus(InviteStatusEnum::REVOKED);
    }

    private function changeStatus(InviteStatusEnum $status) {
        $this->status = $status;
        $this->touchUpdatedAt();
    }

    public function setStatus(InviteStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getIssuedAt(): \DateTimeImmutable
    {
        return $this->issuedAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function touchUpdatedAt(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getTeam(): Team
    {
        return $this->team;
    }
}
