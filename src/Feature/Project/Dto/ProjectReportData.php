<?php

declare(strict_types=1);

namespace App\Feature\Project\Dto;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

final class ProjectReportData
{
    #[Assert\NotBlank]
    #[Assert\Url]
    private string $reportUrl = '';

    #[Assert\NotBlank]
    #[Assert\Url]
    private string $protocolUrl = '';

    #[Assert\NotBlank]
    #[Assert\Url]
    private string $newsUrl = '';

    #[Assert\Count(min: 1, minMessage: 'You must specify at least one finalist')]
    private array $finalists = [];

    public function getReportUrl(): string
    {
        return $this->reportUrl;
    }

    public function setReportUrl(string $reportUrl): void
    {
        $this->reportUrl = $reportUrl;
    }

    public function getProtocolUrl(): string
    {
        return $this->protocolUrl;
    }

    public function setProtocolUrl(string $protocolUrl): void
    {
        $this->protocolUrl = $protocolUrl;
    }

    public function getNewsUrl(): string
    {
        return $this->newsUrl;
    }

    public function setNewsUrl(string $newsUrl): void
    {
        $this->newsUrl = $newsUrl;
    }

    public function getFinalists(): array
    {
        return $this->finalists;
    }

    public function setFinalists(array|ArrayCollection $finalists): void
    {
        if ($finalists instanceof ArrayCollection) {
            $finalists = $finalists->toArray();
        }

        $this->finalists = $finalists;
    }
}
