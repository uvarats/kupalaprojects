<?php

declare(strict_types=1);

namespace App\Feature\Project\Repository;

use App\Entity\Project;
use App\Entity\ProjectTeam;
use App\Feature\Project\Collection\ProjectTeamCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProjectTeam>
 */
class ProjectTeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectTeam::class);
    }

    public function findByProject(Project $project): ProjectTeamCollection
    {
        $result = $this->createQueryBuilder('pt')
            ->select('pt', 'project', 'team', 'teamParticipants', 'participant')
            ->innerJoin('pt.project', 'project', Join::WITH, 'pt.project = :project')
            ->leftJoin('pt.team', 'team')
            ->leftJoin('team.teamParticipants', 'teamParticipants')
            ->leftJoin('teamParticipants.participant', 'participant')
            ->setParameter('project', $project)
            ->getQuery()
            ->getResult();

        return new ProjectTeamCollection($result);
    }

    //    /**
    //     * @return ProjectTeam[] Returns an array of ProjectTeam objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ProjectTeam
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
