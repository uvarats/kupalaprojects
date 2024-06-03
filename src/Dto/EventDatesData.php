<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\EventDates;
use App\Interface\HasDateRangeInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class EventDatesData implements HasDateRangeInterface
{
    #[Assert\NotBlank]
    private ?\DateTimeImmutable $startsAt = null;

    #[Assert\NotBlank]
    private ?\DateTimeImmutable $endsAt = null;

    public static function fromDates(EventDates $dates): EventDatesData
    {
        $instance = new self();

        $instance->startsAt = $dates->getStartsAt();
        $instance->endsAt = $dates->getEndsAt();

        return $instance;
    }

    public function getStartsAt(): ?\DateTimeImmutable
    {
        return $this->startsAt;
    }

    public function setStartsAt(?\DateTimeImmutable $startsAt): void
    {
        $this->startsAt = $startsAt;
    }

    public function getEndsAt(): ?\DateTimeImmutable
    {
        return $this->endsAt;
    }

    public function setEndsAt(?\DateTimeImmutable $endsAt): void
    {
        $this->endsAt = $endsAt;
    }
}
