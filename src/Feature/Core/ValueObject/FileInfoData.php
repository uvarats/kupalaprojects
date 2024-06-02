<?php

declare(strict_types=1);

namespace App\Feature\Core\ValueObject;

final readonly class FileInfoData
{
    public function __construct(
        private string $fileName,
        private string $directory,
    ) {}

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getDirectory(): string
    {
        return $this->directory;
    }

    public function getFullPath(): string
    {
        return $this->directory . DIRECTORY_SEPARATOR . $this->fileName;
    }
}
