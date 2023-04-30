<?php

namespace App\Repository;

use App\Entity\Festival;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Festival>
 *
 * @method Festival|null find($id, $lockMode = null, $lockVersion = null)
 * @method Festival|null findOneBy(array $criteria, array $orderBy = null)
 * @method Festival[]    findAll()
 * @method Festival[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FestivalRepository extends ServiceEntityRepository
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
        return $this->createQueryBuilder('festival')
            ->where('festival.isActive = true')
            ->addOrderBy('festival.startsAt')
            ->addOrderBy('festival.endsAt')
            ->getQuery();
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

//    /**
//     * @return Festival[] Returns an array of Festival objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Festival
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
