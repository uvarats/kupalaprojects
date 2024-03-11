<?php

declare(strict_types=1);

namespace App\Exception\Name;

use Throwable;

final class EmptyNameException extends \DomainException
{
    private function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function lastName(): EmptyNameException
    {
        return new self('Last name must be non-empty string');
    }

    public static function firstName(): EmptyNameException
    {
        return new self('First name must be non-empty string');
    }

    public static function middleName(): EmptyNameException
    {
        return new self('Middle name must be non-empty string (if filled)');
    }
}
