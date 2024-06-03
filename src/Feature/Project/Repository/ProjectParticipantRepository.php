<?php

declare(strict_types=1);

namespace App\Feature\Project\Repository;

use App\Entity\Participant;
use App\Entity\Project;
use App\Entity\ProjectParticipant;
use App\Entity\User;
use App\Enum\AcceptanceEnum;
use App\Feature\Participant\Collection\ParticipantCollection;
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
            ->andWhere('project_participant.project = :project')
            //->leftJoin('project_participant.participant', 'participant')
            ->setParameter('project', $project)
            ->getQuery()
            ->getResult();

        return new ProjectParticipantCollection($result);
    }

    public function findAllForUser(User $user): ProjectParticipantCollection
    {
        $qb = $this->createQueryBuilder('pp')
            ->select('pp', 'participant', 'project')
            ->leftJoin('pp.participant', 'participant')
            ->leftJoin('pp.project', 'project')
            ->where('participant.account = :user')
            ->setParameter('user', $user);

        $result = $qb
            ->getQuery()
            ->getResult();

        return new ProjectParticipantCollection($result);
    }

    public function findAlreadyParticipating(Project $project, ParticipantCollection $participants): ProjectParticipantCollection
    {
        $qb = $this->createQueryBuilder('pp');

        $result = $qb->where($qb->expr()->eq('pp.project', ':project'))
            ->andWhere($qb->expr()->in('pp.participant', ':participants'))
            ->setParameter('project', $project)
            ->setParameter('participants', $participants)
            ->getQuery()
            ->getResult();

        return new ProjectParticipantCollection($result);
    }

}
