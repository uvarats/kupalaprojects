<?php

declare(strict_types=1);

namespace App\Feature\Team\Repository;

use App\Entity\Team;
use App\Entity\TeamInvite;
use App\Feature\Team\Collection\TeamInviteCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TeamInvite>
 */
class TeamInviteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeamInvite::class);
    }

    public function findAllByTeam(Team $team): TeamInviteCollection
    {
        $result = $this->createQueryBuilder('ti')
            ->select('ti', 'recipient')
            ->leftJoin('ti.recipient', 'recipient')
            ->where('ti.team = :team')
            ->setParameter('team', $team)
            ->orderBy('ti.issuedAt', 'DESC')
            ->getQuery()
            ->getResult();

        return new TeamInviteCollection($result);
    }
}
