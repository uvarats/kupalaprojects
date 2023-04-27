<?php

declare(strict_types=1);

namespace App\Dto;

use App\Enum\ProjectCreateStatusEnum;

final readonly class ProjectCreateResult
{
    public function __construct(
        public ProjectCreateStatusEnum $status,
        public ?UserPassword $password = null,
    ) {

    }
}
