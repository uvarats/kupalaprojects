<?php

declare(strict_types=1);

namespace App\Collection;

use App\FormConfig\FieldConfig;

/**
 * @extends Collection<FieldConfig>
 */
final class FieldConfigCollection extends Collection
{
    public function getType(): string
    {
        return FieldConfig::class;
    }
}
