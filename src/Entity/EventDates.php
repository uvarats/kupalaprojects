<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interface\HasDateRangeInterface;
use App\Validator\FestivalDatesValidator;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Embeddable]
final class EventDates
{
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $startsAt;
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $endsAt;

    public static function make(\DateTimeImmutable $startsAt, \DateTimeImmutable $endsAt): self
    {
        if ($startsAt > $endsAt) {
            throw new \LogicException('Event start date can not be after end date.');
        }

        $self = new self();

        $self->startsAt = $startsAt;
        $self->endsAt = $endsAt;

        return $self;
    }

    public function getStartsAt(): \DateTimeImmutable
    {
        return $this->startsAt;
    }

    public function getEndsAt(): \DateTimeImmutable
    {
        return $this->endsAt;
    }


}
