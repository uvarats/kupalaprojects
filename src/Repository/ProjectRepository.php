<?php

namespace App\Repository;

use App\Entity\Festival;
use App\Entity\Project;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 *
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function save(Project $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Project $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getUserProjectsQuery(User $user): Query
    {
        return $this->createQueryBuilder('project')
            ->leftJoin('project.author', 'author')
            ->addSelect('author')
            ->where('author.userEntity = :user')
            ->setParameter('user', $user)
            ->getQuery();
    }

    public function eagerLoad(string $id): ?Project
    {
        return $this->createQueryBuilder('project')
            ->leftJoin('project.author', 'author')
            ->addSelect('author')
            ->leftJoin('project.subjects', 'subjects')
            ->addSelect('subjects')
            ->leftJoin('project.orientedOn', 'orientedOn')
            ->addSelect('orientedOn')
            ->leftJoin('project.festival', 'festival')
            ->addSelect('festival')
            ->where('project.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }

    public function getFestivalProjectsQuery(Festival $festival): Query
    {
        return $this->createQueryBuilder('project')
            ->leftJoin('project.author', 'author')
            ->addSelect('author')
            ->leftJoin('project.subjects', 'subjects')
            ->addSelect('subjects')
            ->leftJoin('project.orientedOn', 'orientedOn')
            ->addSelect('orientedOn')
            ->leftJoin('project.festival', 'festival')
            ->addSelect('festival')
            ->where('festival = :festival')
            ->setParameter('festival', $festival)
            ->getQuery();
    }

//    /**
//     * @return Project[] Returns an array of Project objects
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

//    public function findOneBySomeField($value): ?Project
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
