<?php

declare(strict_types=1);

namespace App\Validator;

use App\Validator\Constraint\UniqueInEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Contracts\Translation\TranslatorInterface;

final class UniqueInEntityValidator extends ConstraintValidator
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly TranslatorInterface $translator,
    ) {}

    #[\Override]
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueInEntity) {
            throw new UnexpectedTypeException($constraint, UniqueInEntity::class);
        }

        if ($value === null) {
            return;
        }

        if (!is_object($value)) {
            throw new UnexpectedValueException($value, 'object');
        }

        $fields = $constraint->fields;
        if (empty($fields)) {
            throw new \LogicException('Fields array must not be empty when using UniqueInEntity constraint');
        }

        $entityClass = $constraint->entityClass;
        if (!class_exists($entityClass)) {
            throw new \LogicException('Class ' . $entityClass . ' not found!');
        }

        $accessor = PropertyAccess::createPropertyAccessor();

        $criteriaArray = [];
        foreach($fields as $field) {
            $criteriaArray[$field] = $accessor->getValue($value, $field);
        }

        $repository = $this->em->getRepository($entityClass);

        $entries = $repository->findBy($criteriaArray);

        if (count($entries) > 0) {
            $message = $this->translator->trans($constraint->message, [], 'messages');
            $this->context->buildViolation($message)
                ->addViolation();
        }
    }
}
