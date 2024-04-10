<?php

declare(strict_types=1);

namespace App\Validator\Constraint;

use App\Validator\UniqueInEntityValidator;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UniqueInEntity extends Constraint
{
    #[HasNamedArguments]
    public function __construct(
        public string $entityClass,
        public array $fields,
        public string $message = 'Entity already exists',
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct([], $groups, $payload);
    }

    public function validatedBy(): string
    {
        return UniqueInEntityValidator::class;
    }
}
