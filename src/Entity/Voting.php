<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\VoteEnum;
use App\Enum\VoteSubmitResultEnum;
use App\Enum\VotingStateEnum;
use App\Repository\VotingRepository;
use App\ValueObject\JuryMember;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: VotingRepository::class)]
class Voting
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private Project $project;

    /**
     * @var Collection<int, Vote>
     */
    #[ORM\OneToMany(mappedBy: 'voting', targetEntity: Vote::class, orphanRemoval: true)]
    private Collection $votes;

    #[ORM\Column(length: 255, enumType: VotingStateEnum::class)]
    private VotingStateEnum $state = VotingStateEnum::INACTIVE;

    public function __construct()
    {
        $this->votes = new ArrayCollection();
    }

    public static function make(Project $project): Voting
    {
        $instance = new self();

        $instance->project = $project;

        return $instance;
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    /**
     * @return Collection<int, Vote>
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function vote(JuryMember $juryMember, VoteEnum $decision): VoteSubmitResultEnum
    {
        if ($this->alreadyVotedBy($juryMember)) {
            return VoteSubmitResultEnum::ALREADY_VOTED;
        }

        $vote = Vote::make(
            juryMember: $juryMember->getUser(),
            decision: $decision,
            voting: $this,
        );

        $this->votes->add($vote);

        return VoteSubmitResultEnum::VOTE_ACCEPTED;
    }

    private function alreadyVotedBy(JuryMember $juryMember): bool
    {
        foreach ($this->votes as $vote) {
            if ($vote->getJuryMember() === $juryMember->getUser()) {
                return true;
            }
        }

        return false;
    }

    public function getState(): VotingStateEnum
    {
        return $this->state;
    }

    public function setState(VotingStateEnum $state): static
    {
        $this->state = $state;

        return $this;
    }
}
