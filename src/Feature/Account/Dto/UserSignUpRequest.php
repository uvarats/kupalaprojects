<?php

declare(strict_types=1);

namespace App\Feature\Account\Dto;

use App\Feature\Account\ValueObject\Password;
use App\ValueObject\Email;
use App\ValueObject\PersonName;

final readonly class UserSignUpRequest
{
    private function __construct(
        private PersonName $name,
        private Email $email,
        private Password $password,
    ) {}

    public static function make(PersonName $name, Email $email, Password $password): UserSignUpRequest
    {
        return new self(
            name: $name,
            email: $email,
            password: $password,
        );
    }

    public function getName(): PersonName
    {
        return $this->name;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }
}
