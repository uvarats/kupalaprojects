<?php

namespace App\Repository;

use App\Entity\Festival;
use App\Entity\FestivalMail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FestivalMail>
 *
 * @method FestivalMail|null find($id, $lockMode = null, $lockVersion = null)
 * @method FestivalMail|null findOneBy(array $criteria, array $orderBy = null)
 * @method FestivalMail[]    findAll()
 * @method FestivalMail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FestivalMailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FestivalMail::class);
    }

    public function save(FestivalMail $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FestivalMail $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getMail(string $id): FestivalMail
    {
        return $this->getEagerLoadBuilder()
            ->where('mail.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }

    public function getFestivalMailQuery(Festival $festival): Query
    {
        return $this->getEagerLoadBuilder()
            ->where('mail.festival = :festival')
            ->setParameter('festival', $festival)
            ->orderBy('mail.sentAt', 'DESC')
            ->getQuery();
    }

    private function getEagerLoadBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('mail')
            ->leftJoin('mail.festival', 'festival')
            ->addSelect('festival')
            ->leftJoin('mail.mailAuthor', 'author')
            ->addSelect('author');
    }

//    /**
//     * @return FestivalMail[] Returns an array of FestivalMail objects
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

//    public function findOneBySomeField($value): ?FestivalMail
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
