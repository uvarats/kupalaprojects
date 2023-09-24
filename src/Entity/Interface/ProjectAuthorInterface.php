<?php

declare(strict_types=1);

namespace App\Entity\Interface;

use App\Entity\User;

interface ProjectAuthorInterface
{
    public function getUserEntity(): ?User;
    public function getPlaceOfWork(): ?string;
    public function getOccupation(): ?string;
    public function getReserveEmail(): ?string;
}
