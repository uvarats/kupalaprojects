<?php

declare(strict_types=1);

namespace App\Entity;

use App\Feature\Festival\Repository\FestivalMailRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: FestivalMailRepository::class)]
class FestivalMail
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(targetEntity: Festival::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Festival $festival = null;

    #[ORM\Column(length: 255)]
    private ?string $subject = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $mailAuthor = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $sentAt = null;

    #[ORM\Column(options: ['default' => '[]'])]
    private array $recipients = [];

    #[ORM\Column(options: ['default' => '[]'])]
    private array $bcc = [];

    #[ORM\Column(options: ['default' => '[]'])]
    private array $cc = [];

    public static function create(
        Festival $festival,
        User $author,
        string $subject,
        string $content,
        array $recipients,
        array $cc,
        array $bcc,
    ): FestivalMail {
        $mail = new self();

        $mail->festival = $festival;
        $mail->mailAuthor = $author;
        $mail->subject = $subject;
        $mail->content = $content;
        $mail->recipients = $recipients;
        $mail->cc = $cc;
        $mail->bcc = $bcc;

        return $mail;
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getFestival(): ?Festival
    {
        return $this->festival;
    }

    public function setFestival(?Festival $festival): self
    {
        $this->festival = $festival;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getMailAuthor(): ?User
    {
        return $this->mailAuthor;
    }

    public function setMailAuthor(?User $mailAuthor): self
    {
        $this->mailAuthor = $mailAuthor;

        return $this;
    }

    public function getSentAt(): ?\DateTimeImmutable
    {
        return $this->sentAt;
    }

    public function setSentAt(\DateTimeImmutable $sentAt): self
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    public function getRecipients(): array
    {
        return $this->recipients;
    }

    public function setRecipients(array $recipients): static
    {
        $this->recipients = $recipients;

        return $this;
    }

    public function getBcc(): array
    {
        return $this->bcc;
    }

    public function setBcc(array $bcc): static
    {
        $this->bcc = $bcc;

        return $this;
    }

    public function getCc(): array
    {
        return $this->cc;
    }

    public function setCc(array $cc): static
    {
        $this->cc = $cc;

        return $this;
    }
}
