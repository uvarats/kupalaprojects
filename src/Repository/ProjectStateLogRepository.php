<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ProjectStateLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProjectStateLog>
 *
 * @method ProjectStateLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectStateLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectStateLog[]    findAll()
 * @method ProjectStateLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectStateLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectStateLog::class);
    }

    //    /**
    //     * @return ProjectStateLog[] Returns an array of ProjectStateLog objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ProjectStateLog
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
