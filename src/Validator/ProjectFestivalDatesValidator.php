<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\Festival;
use App\Entity\Project;
use App\Interface\HasDateRangeInterface;
use Cake\Chronos\Chronos;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final readonly class ProjectFestivalDatesValidator
{
    public function __construct(private ?Festival $festival) {}

    public function validate(HasDateRangeInterface $range, ExecutionContextInterface $context, $payload): void
    {
        $startsAt = new Chronos($range->getStartsAt());
        $endsAt = new Chronos($range->getEndsAt());

        if ($endsAt->lessThanOrEquals($startsAt)) {
            $context->buildViolation('Дата завершения не может быть равна, либо до даты начала')
                ->atPath('endsAt')
                ->addViolation();
        }

        $festival = $this->festival;

        if ($festival === null) {
            return;
        }

        $festivalStartsAt = new Chronos($festival->getStartsAt());
        $festivalEndsAt = new Chronos($festival->getEndsAt());

        $message = 'Даты проекта должны быть в пределах дат фестиваля';
        if ($festivalStartsAt->greaterThan($startsAt)) {
            $context->buildViolation($message)
                ->atPath('startsAt')
                ->addViolation();
        }

        if ($festivalEndsAt->lessThan($endsAt)) {
            $context->buildViolation($message)
                ->atPath('endsAt')
                ->addViolation();
        }
    }
}
