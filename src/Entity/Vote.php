<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\VoteEnum;
use App\Repository\VoteRepository;
use App\ValueObject\Entity\VoteId;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: VoteRepository::class)]
class Vote
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private User $juryMember;

    #[ORM\Column(type: 'integer', enumType: VoteEnum::class)]
    private VoteEnum $decision;

    #[ORM\ManyToOne(inversedBy: 'votes')]
    #[ORM\JoinColumn(nullable: false)]
    private Voting $voting;

    private function __construct() {}

    public static function make(
        User $juryMember,
        VoteEnum $decision,
        Voting $voting,
    ): Vote {
        $instance = new self();

        $instance->juryMember = $juryMember;
        $instance->decision = $decision;
        $instance->voting = $voting;

        return $instance;
    }

    public function getId(): ?VoteId
    {
        return VoteId::make($this->id);
    }

    public function getJuryMember(): User
    {
        return $this->juryMember;
    }

    public function getDecision(): VoteEnum
    {
        return $this->decision;
    }

    public function setDecision(VoteEnum $decision): static
    {
        $this->decision = $decision;

        return $this;
    }

    public function getVoting(): Voting
    {
        return $this->voting;
    }
}
