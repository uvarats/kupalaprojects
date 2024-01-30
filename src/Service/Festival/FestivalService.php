<?php

declare(strict_types=1);

namespace App\Service\Festival;

use App\Collection\FestivalDatesCollection;
use App\Dto\FestivalDates;
use App\Entity\Festival;
use App\Factory\FestivalDatesFactory;
use App\Repository\FestivalRepository;

final readonly class FestivalService
{
    public function __construct(
        private FestivalRepository $festivalRepository,
        private FestivalDatesFactory $datesFactory,
    ) {}

    public function getActiveFestivalsDates(): FestivalDatesCollection
    {
        $dates = $this->festivalRepository->getActiveFestivalsDates();

        return $this->datesFactory->collection($dates);
    }

    public function getFestivalDates(Festival $festival): FestivalDates
    {
        return $this->datesFactory->makeFromFestival($festival);
    }
}
