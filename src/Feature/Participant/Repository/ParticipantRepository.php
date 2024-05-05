<?php

declare(strict_types=1);

namespace App\Feature\Participant\Repository;

use App\Entity\Participant;
use App\Entity\User;
use App\Feature\Core\Collection\EmailCollection;
use App\Feature\Participant\Collection\ParticipantCollection;
use App\Feature\Participant\ValueObject\ParticipantId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Participant>
 *
 * @method Participant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participant[]    findAll()
 * @method Participant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participant::class);
    }

    public function save(Participant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Participant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneForUser(User $user): ?Participant {
        return $this->createQueryBuilder('participant')
            ->where('participant.account = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findById(ParticipantId $id): ?Participant
    {
        return $this->createQueryBuilder('p')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByEmails(EmailCollection $emails): ParticipantCollection
    {
        $participants = $this->createQueryBuilder('p')
            ->where('p.email in (:emails)')
            ->setParameter('emails', $emails->toArray())
            ->getQuery()
            ->getResult();

        return new ParticipantCollection($participants);
    }
}
