<?php

declare(strict_types=1);

namespace App\Feature\Team\Repository;

use App\Entity\Team;
use App\Entity\TeamParticipant;
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

    public function findActiveByTeam(Team $team): TeamParticipantCollection
    {
        $result = $this->createQueryBuilder('tp')
            ->select('tp', 'participant')
            ->leftJoin('tp.participant', 'participant')
            ->where('tp.team = :team')
            ->andWhere('tp.leftAt is null')
            ->setParameter('team', $team)
            ->orderBy('tp.joinedAt', 'ASC')
            ->getQuery()
            ->getResult();

        return new TeamParticipantCollection($result);
    }
}
