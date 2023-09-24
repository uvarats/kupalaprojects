<?php

namespace App\Repository;

use App\Entity\ProjectSubject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProjectSubject>
 *
 * @method ProjectSubject|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectSubject|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectSubject[]    findAll()
 * @method ProjectSubject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectSubjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectSubject::class);
    }

    public function save(ProjectSubject $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProjectSubject $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getQuery(): Query
    {
        return $this->createQueryBuilder('project_subject')
            ->getQuery();
    }

    //    /**
    //     * @return ProjectSubject[] Returns an array of ProjectSubject objects
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

    //    public function findOneBySomeField($value): ?ProjectSubject
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
