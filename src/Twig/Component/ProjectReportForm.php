<?php

declare(strict_types=1);

namespace App\Twig\Component;

use App\Entity\Project;
use App\Feature\Project\Dto\ProjectReportData;
use App\Feature\Project\Form\ProjectReportType;
use App\Feature\Project\Repository\ProjectParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class ProjectReportForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public Project $project;

    #[LiveProp]
    public bool $saved = false;

    protected function instantiateForm(): FormInterface
    {
        $initialData = $this->resolveInitialData();

        return $this->createForm(ProjectReportType::class, $initialData, ['project' => $this->project]);
    }

    private function resolveInitialData(): ?ProjectReportData
    {
        $report = $this->project->getProjectReport();

        if ($report === null) {
            return null;
        }

        $data = new ProjectReportData();

        $newsUrl = $report->getNewsUrl();
        $data->setNewsUrl($newsUrl);

        $reportUrl = $report->getReportUrl();
        $data->setReportUrl($reportUrl);

        $protocolUrl = $report->getProtocolUrl();
        $data->setProtocolUrl($protocolUrl);

        $finalists = $report->getFinalists()->toArray();
        $data->setFinalists($finalists);

        return $data;
    }

    #[LiveAction]
    public function selectAllParticipants(ProjectParticipantRepository $participantRepository): void
    {
        $projectParticipants = $participantRepository->findAllApproved($this->project);
        $this->formValues['finalists'] = [];

        foreach ($projectParticipants as $projectParticipant) {
            $finalistId = $projectParticipant->getId();

            $this->formValues['finalists'][] = $finalistId->toString();
        }
    }

    #[LiveAction]
    public function unselectAllParticipants(): void
    {
        $this->formValues['finalists'] = [];
    }

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager): void
    {
        $this->submitForm();

        $data = $this->getForm()->getData();
        assert($data instanceof ProjectReportData);

        $reportUrl = $data->getReportUrl();
        $protocolUrl = $data->getProtocolUrl();
        $newsUrl = $data->getNewsUrl();
        $finalists = $data->getFinalists();
        if (!$this->project->hasReport()) {
            $this->project->createReport(
                reportUrl: $reportUrl,
                protocolUrl: $protocolUrl,
                newsUrl: $newsUrl,
                finalists: $finalists,
            );
        } else {
            $report = $this->project->getProjectReport();

            $report->setReportUrl($reportUrl);
            $report->setProtocolUrl($protocolUrl);
            $report->setNewsUrl($newsUrl);
            $report->syncFinalists($finalists);
        }

        $entityManager->flush();
        $this->saved = true;
    }
}
