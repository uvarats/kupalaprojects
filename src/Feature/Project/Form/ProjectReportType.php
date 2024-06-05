<?php

declare(strict_types=1);

namespace App\Feature\Project\Form;

use App\Entity\Project;
use App\Entity\ProjectParticipant;
use App\Enum\AcceptanceEnum;
use App\Feature\Project\Dto\ProjectReportData;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ProjectReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $project = $options['project'];

        if (!$project instanceof Project) {
            throw new \LogicException('Specify valid project for project report form.');
        }

        $builder
            ->add('reportUrl', UrlType::class, [
                'empty_data' => '',
                'label' => 'project.report.report_url',
            ])
            ->add('protocolUrl', UrlType::class, [
                'empty_data' => '',
                'label' => 'project.report.protocol_url',
            ])
            ->add('newsUrl', UrlType::class, [
                'empty_data' => '',
                'label' => 'project.report.news_url',
            ])
            ->add('finalists', EntityType::class, [
                'class' => ProjectParticipant::class,
                'choice_label' => function (ProjectParticipant $projectParticipant): string {
                    return $projectParticipant->getFullNameWithEmail();
                },
                'query_builder' => function (EntityRepository $er) use ($project): QueryBuilder {
                    return $er->createQueryBuilder('pp')
                        ->where('pp.project = :project')
                        ->andWhere('pp.acceptance = :acceptance')
                        ->setParameter('project', $project)
                        ->setParameter('acceptance', AcceptanceEnum::APPROVED);
                },
                'multiple' => true,
                'expanded' => true,
                'label' => 'project.report.finalists',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProjectReportData::class,
            'project' => null,
        ]);

        $resolver->setAllowedTypes('project', ['null', Project::class]);
    }
}
