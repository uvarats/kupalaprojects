<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\FestivalPeriodEnum;

final readonly class FestivalPeriodService
{
    public function getPeriodDates(FestivalPeriodEnum $periodEnum)
    {
        return match ($periodEnum) {
            FestivalPeriodEnum::AUTUMN => throw new \Exception('To be implemented'),
            FestivalPeriodEnum::WINTER => throw new \Exception('To be implemented'),
            FestivalPeriodEnum::SPRING => throw new \Exception('To be implemented'),
        };
    }
}
