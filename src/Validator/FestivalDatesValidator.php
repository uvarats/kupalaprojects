<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\Festival;
use Cake\Chronos\Chronos;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class FestivalDatesValidator
{
    public static function validate(Festival $festival, ExecutionContextInterface $context, $payload): void
    {
        $startsAt = new Chronos($festival->getStartsAt());
        $endsAt = new Chronos($festival->getEndsAt());

        $yearStart = Chronos::now()->firstOfYear();

        $message = 'Дата начала не может быть в прошлом году.';
        if ($startsAt->lessThan($yearStart)) {
            $context->buildViolation($message)
                ->atPath('startsAt')
                ->addViolation();
        }

        if ($endsAt->lessThan($yearStart)) {
            $context->buildViolation($message)
                ->atPath('endsAt')
                ->addViolation();
        }

        if ($endsAt->lessThanOrEquals($startsAt)) {
            $context->buildViolation('Дата завершения не может быть равна, либо до даты начала')
                ->atPath('endsAt')
                ->addViolation();
        }
    }
}
