<?php

declare(strict_types=1);

namespace App\Feature\Project\Repository;

use App\Entity\Project;
use App\Entity\ProjectParticipant;
use App\Enum\AcceptanceEnum;
use App\Feature\Project\Collection\ProjectParticipantCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProjectParticipant>
 *
 * @method ProjectParticipant|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectParticipant|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectParticipant[]    findAll()
 * @method ProjectParticipant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectParticipant::class);
    }

    public function findAllWithoutDecision(Project $project): ProjectParticipantCollection
    {
        $result = $this->createQueryBuilder('project_participant')
            // todo test preload
            //->select('participant')
            ->where('project_participant.acceptance = :acceptance')
            ->andWhere('project_participant.project = :project')
            //->leftJoin('project_participants.participant', 'participant')
            ->setParameter('acceptance', AcceptanceEnum::NO_DECISION->value)
            ->setParameter('project', $project)
            ->getQuery()
            ->getResult();

        return new ProjectParticipantCollection($result);
    }
}
