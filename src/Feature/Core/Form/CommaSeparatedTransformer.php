<?php

declare(strict_types=1);

namespace App\Feature\Core\Form;

use Symfony\Component\Form\DataTransformerInterface;

final class CommaSeparatedTransformer implements DataTransformerInterface
{

    #[\Override]
    public function transform(mixed $value): string
    {
        if ($value === null) {
            return '';
        }

        if (!is_array($value)) {
            return '';
        }

        return implode(',', $value);
    }

    #[\Override]
    public function reverseTransform(mixed $value): array
    {
        if ($value === null) {
            return [];
        }

        if (!is_string($value)) {
            return [];
        }

        return explode(',', $value);
    }
}
