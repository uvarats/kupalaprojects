<?php

declare(strict_types=1);

namespace App\Collection;

use App\FormConfig\FieldConfig;
use IteratorAggregate;
use Ramsey\Collection\AbstractCollection;

/**
 * @extends AbstractCollection<FieldConfig>
 *
 * @implements IteratorAggregate<FieldConfig>
 */
final class FieldConfigCollection extends AbstractCollection
{

    public function getType(): string
    {
        return FieldConfig::class;
    }
}
