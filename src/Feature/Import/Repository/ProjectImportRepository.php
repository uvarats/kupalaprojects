<?php

declare(strict_types=1);

namespace App\Feature\Import\Repository;

use App\Entity\Project;
use App\Entity\ProjectImport;
use App\Feature\Import\Collection\ProjectImportCollection;
use App\Feature\Import\Enum\ImportTypeEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use function Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<ProjectImport>
 */
class ProjectImportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectImport::class);
    }

    public function findParticipantImportsForProject(Project $project): ProjectImportCollection
    {
        $qb = $this->createQueryBuilder('pi');

        $result = $qb->where($qb->expr()->eq('pi.type', ':type'))
            ->andWhere($qb->expr()->eq('pi.project', ':project'))
            ->orderBy('pi.createdAt', 'DESC')
            ->setParameter('type', ImportTypeEnum::PARTICIPANT)
            ->setParameter('project', $project)
            ->getQuery()
            ->getResult();

        return new ProjectImportCollection($result);
    }
}
