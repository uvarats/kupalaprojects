<?php

namespace App\Repository;

use App\Collection\FestivalCollection;
use App\Entity\Festival;
use App\Entity\User;
use App\Repository\Interface\FestivalRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Festival>
 *
 * @method Festival|null find($id, $lockMode = null, $lockVersion = null)
 * @method Festival|null findOneBy(array $criteria, array $orderBy = null)
 * @method Festival[]    findAll()
 * @method Festival[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FestivalRepository extends ServiceEntityRepository implements FestivalRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Festival::class);
    }

    public function save(Festival $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Festival $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function createOrderedQuery(): Query
    {
        return $this->getOrderedBuilder()
            ->getQuery();
    }

    public function getActiveFestivalsDates(): array
    {
        return $this->getOrderedBuilder()
            ->select('festival.id as festivalId')
            ->addSelect('festival.startsAt')
            ->addSelect('festival.endsAt')
            ->getQuery()
            ->getResult();
    }

    public function getOrderedBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('festival')
            ->where('festival.isActive = true')
            ->addOrderBy('festival.startsAt')
            ->addOrderBy('festival.endsAt');
    }

    public function isUserRelatedWithAnyFestival(User $user): bool
    {
        $builder = $this->createQueryBuilder('festival')
            ->leftJoin('festival.jury', 'jury')
            ->leftJoin('festival.organizationCommittee', 'organizationCommittee')
            ->where(':user MEMBER OF festival.jury')
            ->orWhere(':user MEMBER OF festival.organizationCommittee')
            ->andWhere('festival.isActive = true')
            ->setParameter('user', $user);

        return $builder
                ->select('count(festival)')
                ->getQuery()
                ->getSingleScalarResult() > 0;
    }

    #[\Override]
    public function findActive(): FestivalCollection
    {
        $festivals = $this->createActiveQuery()
            ->getQuery()
            ->getResult();

        return new FestivalCollection($festivals);
    }

    public function createActiveQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('festival')
            ->where('festival.isActive = true')
            ->addOrderBy('festival.startsAt')
            ->addOrderBy('festival.endsAt');
    }
}
