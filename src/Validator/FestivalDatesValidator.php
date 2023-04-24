<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\Festival;
use Cake\Chronos\Chronos;
use DateTimeImmutable;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class FestivalDatesValidator
{
    public static function validate(Festival $festival, ExecutionContextInterface $context, $payload) {
        $startsAt = new Chronos($festival->getStartsAt());
        $endsAt = new Chronos($festival->getEndsAt());

        $today = Chronos::today();

        if ($startsAt->lessThan($today)) {
            $context->buildViolation('Дата не может быть меньше текущей.')
                ->atPath('startsAt')
                ->addViolation();
        }

        if ($endsAt->lessThan($today)) {
            $context->buildViolation('Дата не может быть меньше текущей.')
                ->atPath('endsAt')
                ->addViolation();
        }

        //TODO
    }
}
