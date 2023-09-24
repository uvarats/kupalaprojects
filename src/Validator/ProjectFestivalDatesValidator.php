<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\Project;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ProjectFestivalDatesValidator
{
    public static function validate(Project $festival, ExecutionContextInterface $context, $payload): void {}
}
