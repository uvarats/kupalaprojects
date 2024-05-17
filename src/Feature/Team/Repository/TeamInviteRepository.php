<?php

declare(strict_types=1);

namespace App\Feature\Team\Repository;

use App\Entity\Participant;
use App\Entity\Team;
use App\Entity\TeamInvite;
use App\Feature\Core\Collection\EmailCollection;
use App\Feature\Team\Collection\TeamInviteCollection;
use App\Feature\Team\Enum\InviteStatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
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

    public function findAllPendingByRecipient(Participant $participant): TeamInviteCollection
    {
        $result = $this->createQueryBuilder('ti')
            ->select('ti', 'recipient', 'issuer', 'team')
            ->join('ti.recipient', 'recipient')
            ->join('ti.issuer', 'issuer')
            ->join('ti.team', 'team')
            ->where('ti.status = :status')
            ->andWhere('ti.recipient = :recipient')
            ->setParameter('recipient', $participant)
            ->setParameter('status', InviteStatusEnum::PENDING)
            ->orderBy('ti.issuedAt', 'DESC')
            ->getQuery()
            ->getResult();

        return new TeamInviteCollection($result);
    }

    public function findByEmails(EmailCollection $emails): TeamInviteCollection
    {
        $qb = $this->createQueryBuilder('ti');

        $result = $qb
            ->select('ti', 'recipient')
            ->innerJoin('ti.recipient', 'recipient')
            ->where($qb->expr()->in('recipient.email', ':emails'))
            ->andWhere($qb->expr()->eq('ti.status', ':status'))
            ->setParameter('emails', $emails->toArray())
            ->setParameter('status', InviteStatusEnum::PENDING)
            ->getQuery()
            ->getResult();

        return new TeamInviteCollection($result);
    }
}
