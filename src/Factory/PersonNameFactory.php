<?php

declare(strict_types=1);

namespace App\Factory;

use App\Dto\PersonNameData;
use App\Entity\Embeddable\PersonName;

final readonly class PersonNameFactory
{
    public function fromData(PersonNameData $data): PersonName
    {
        return PersonName::make(
            lastName: $data->lastName,
            firstName: $data->firstName,
            middleName: $data->middleName,
        );
    }
}
