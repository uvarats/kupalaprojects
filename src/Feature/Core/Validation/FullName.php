<?php

declare(strict_types=1);

namespace App\Feature\Core\Validation;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class FullName extends Constraint
{
    public string $message = 'Invalid full name. Full name must consist from 2 or 3 space-separated words.';

    public function __construct(?string $message = null, ?array $groups = null, $payload = null) {
        parent::__construct([], $groups, $payload);

        $this->message = $message ?? $this->message;
    }
}
