<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Participant;
use App\Entity\Team;
use App\Enum\TeamParticipantRoleEnum;
use App\Feature\Team\Collection\TeamCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Team>
 *
 * @method Team|null find($id, $lockMode = null, $lockVersion = null)
 * @method Team|null findOneBy(array $criteria, array $orderBy = null)
 * @method Team[]    findAll()
 * @method Team[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    public function save(Team $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Team $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findCreatedByParticipant(Participant $participant): TeamCollection
    {
        $result = $this->createParticipantAndRoleQuery($participant, TeamParticipantRoleEnum::CREATOR)
            ->getQuery()
            ->getResult();

        return new TeamCollection($result);
    }

    public function findParticipatedByParticipant(Participant $participant): TeamCollection
    {
        $result = $this->createParticipantAndRoleQuery($participant, TeamParticipantRoleEnum::GENERAL_PARTICIPANT)
            ->getQuery()
            ->getResult();

        return new TeamCollection($result);
    }

    private function createParticipantAndRoleQuery(Participant $participant, TeamParticipantRoleEnum $role): QueryBuilder
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.teamParticipants', 'tp')
            ->where('tp.participant = :participant')
            ->andWhere('tp.role = :role')
            ->setParameter('participant', $participant)
            ->setParameter('role', $role);
    }
}
