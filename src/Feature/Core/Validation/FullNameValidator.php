<?php

declare(strict_types=1);

namespace App\Feature\Core\Validation;

use App\ValueObject\PersonName;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class FullNameValidator extends ConstraintValidator
{

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof FullName) {
            throw new UnexpectedTypeException($constraint, FullName::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $parts = explode(' ', $value);
        $partsCount = count($parts);
        if ($partsCount < 2 || $partsCount > 3) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
