<?php

declare(strict_types=1);

namespace App\Factory;

use App\Collection\FestivalDatesCollection;
use App\Dto\FestivalDates;
use App\Entity\Festival;
use DateTimeImmutable;

final readonly class FestivalDatesFactory
{
    public function collection(array $data): FestivalDatesCollection
    {
        $collection = new FestivalDatesCollection();

        foreach ($data as $objectData) {
            $object = $this->make($objectData);

            $collection->add($object);
        }

        return $collection;
    }

    public function make(array $data): FestivalDates
    {
        $festivalId = (string)$data['festivalId'];
        /** @var DateTimeImmutable $startsAt */
        $startsAt = $data['startsAt'];
        /** @var DateTimeImmutable $endsAt */
        $endsAt = $data['endsAt'];

        return $this->create(
            festivalId: $festivalId,
            startsAt: $startsAt,
            endsAt: $endsAt,
        );
    }

    public function makeFromFestival(Festival $festival): FestivalDates
    {
        return $this->create(
            festivalId: (string)$festival->getId(),
            startsAt: $festival->getStartsAt(),
            endsAt: $festival->getEndsAt(),
        );
    }

    private function create(
        string $festivalId,
        DateTimeImmutable $startsAt,
        DateTimeImmutable $endsAt
    ): FestivalDates {
        return new FestivalDates(
            festivalId: $festivalId,
            startsAt: $startsAt->format('Y-m-d'),
            endsAt: $endsAt->format('Y-m-d'),
        );
    }
}
