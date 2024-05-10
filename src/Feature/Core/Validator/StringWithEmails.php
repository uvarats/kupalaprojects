<?php

declare(strict_types=1);

namespace App\Feature\Core\Validator;

use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class StringWithEmails extends Constraint
{
    public string $message = 'Некорректный E-mail: {{ email }}';

    #[HasNamedArguments]
    public function __construct(
        public string $separator = ',',
        ?array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct([], $groups, $payload);
    }
}
