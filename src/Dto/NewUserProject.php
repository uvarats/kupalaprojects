<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\Project;

final readonly class NewUserProject
{
    public function __construct(
        public Project $project,
        public UserPassword $userPassword,
    ) {
    }

}
