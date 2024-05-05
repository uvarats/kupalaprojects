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

    public function getIssuer(): ?Participant
    {
        return $this->issuer;
    }

    public function getRecipient(): ?Participant
    {
        return $this->recipient;
    }

    public function getStatus(): InviteStatusEnum
    {
        return $this->status;
    }

    public function revoke(): void
    {
        $this->status = InviteStatusEnum::REVOKED;
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
