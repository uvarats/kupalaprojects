<?php

declare(strict_types=1);

namespace App\Twig\Runtime;

use BadMethodCallException;
use InvalidArgumentException;
use Twig\Extension\RuntimeExtensionInterface;

class EnumExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function enum(string $enumFQN): object
    {
        return new class ($enumFQN) {
            public function __construct(private readonly string $enum)
            {
                if (!enum_exists($this->enum)) {
                    throw new InvalidArgumentException("$this->enum is not an Enum type and cannot be used in this function");
                }
            }

            public function __call(string $name, array $arguments)
            {
                $enumFQN = sprintf('%s::%s', $this->enum, $name);

                if (defined($enumFQN)) {
                    return constant($enumFQN);
                }

                if (method_exists($this->enum, $name)) {
                    return $this->enum::$name(...$arguments);
                }

                throw new BadMethodCallException("Neither \"{$enumFQN}\" or \"{$enumFQN}::{$name}()\" exist in this runtime.");
            }
        };
    }
}
