<?php

declare(strict_types=1);

namespace App\Feature\Core\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class StringWithEmailsValidator extends ConstraintValidator
{
    public function __construct(
        private ValidatorInterface $validator,
    ) {}

    #[\Override]
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof StringWithEmails) {
            throw new UnexpectedTypeException($constraint, StringWithEmails::class);
        }

        if ($value === null || $value === '') {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $emails = explode($constraint->separator, $value);

        $errors = $this->validator->validate($emails, [
            new Assert\All([
                new Assert\Email(),
            ])
        ]);

        if ($errors->count() === 0) {
            return;
        }

        foreach ($errors as $error) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ email }}', $error->getInvalidValue())
                ->addViolation();
        }
    }
}
