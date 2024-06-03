<?php

declare(strict_types=1);

namespace App\Feature\Import\Interface;

use App\Entity\Project;
use App\Feature\Core\ValueObject\FileInfoData;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ProjectStorageInterface
{
    public function upload(Project $project, UploadedFile $file): FileInfoData;
}
