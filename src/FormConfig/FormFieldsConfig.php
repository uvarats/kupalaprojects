<?php

declare(strict_types=1);

namespace App\FormConfig;

use App\Collection\FieldConfigCollection;

final class FormFieldsConfig
{
    private FieldConfigCollection $fieldConfig;

    public function __construct()
    {
        $this->fieldConfig = new FieldConfigCollection();
    }

    public function getConfig(string $field): ?FieldConfig
    {
        return $this->fieldConfig[$field] ?? null;
    }

    public function getConfigArray(string $field): array
    {
        $config = $this->getConfig($field);

        if ($config === null) {
            return [];
        }

        return $config->getConfig();
    }

    public function setConfig(string $field, FieldConfig $config): FormFieldsConfig
    {
        $this->fieldConfig[$field] = $config;

        return $this;
    }

    public function getFieldConfig(): FieldConfigCollection
    {
        return $this->fieldConfig;
    }

}
