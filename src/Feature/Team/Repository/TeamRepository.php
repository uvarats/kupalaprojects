<?php

declare(strict_types=1);

namespace App\Feature\Team\Repository;

use App\Entity\Participant;
use App\Entity\Team;
use App\Enum\TeamParticipantRoleEnum;
use App\Feature\Team\Collection\TeamCollection;
use App\Feature\Team\ValueObject\TeamId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
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

    public function findById(TeamId $id): ?Team
    {
        return $this->createQueryBuilder('t')
            ->where('t.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
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
        $qb = $this->createQueryBuilder('t');

        return $qb
            ->innerJoin(
                't.teamParticipants', 'tp',
                Join::WITH,
                $qb->expr()->andX(
                    $qb->expr()->eq('t.archived', ':archived'),
                    $qb->expr()->eq('tp.participant', ':participant'),
                    $qb->expr()->eq('tp.role', ':role'),
                ),
            )
            // todo: check for already submitted team to project
            //->where($qb->expr()->notIn('t', $this->getEntityManager()->createQueryBuilder()))
            ->setParameter('participant', $participant)
            ->setParameter('role', $role)
            ->setParameter('archived', false);
    }
}
