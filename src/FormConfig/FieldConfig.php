<?php

declare(strict_types=1);

namespace App\FormConfig;

final class FieldConfig
{
    public function __construct(
        private array $config = [],
    ) {}

    public function getConfig(): array
    {
        return $this->config;
    }

    public function setOption(string $option, mixed $value): FieldConfig
    {
        $this->config[$option] = $value;

        return $this;
    }

}
