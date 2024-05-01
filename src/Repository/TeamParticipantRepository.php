<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TeamParticipant;
use App\Entity\User;
use App\Feature\Team\Collection\TeamParticipantCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TeamParticipant>
 *
 * @method TeamParticipant|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeamParticipant|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeamParticipant[]    findAll()
 * @method TeamParticipant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeamParticipant::class);
    }

    public function findAllForUser(User $user): TeamParticipantCollection
    {
        $qb = $this->createQueryBuilder('tp');

        $qb->select('tp', 'participant', 'team', 'project')
            ->leftJoin('tp.participant', 'participant')
            ->leftJoin('tp.team', 'team')
            ->leftJoin('team.project', 'project')
            ->where('participant.account = :user')
            ->setParameter('user', $user);

        $result = $qb
            ->getQuery()
            ->getResult();

        return new TeamParticipantCollection($result);
    }
}
