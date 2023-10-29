<?php

namespace App\Repository;

use App\Collection\ProjectCollection;
use App\Entity\Festival;
use App\Entity\Project;
use App\Entity\User;
use App\Enum\AcceptanceEnum;
use App\Enum\ProjectStateEnum;
use App\Repository\Interface\EagerLoadInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 *
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository implements EagerLoadInterface
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
            ->orderBy('project.id', 'DESC')
            ->setParameter('user', $user)
            ->getQuery();
    }

    public function getProjectWithAwards(string $id): ?Project
    {
        return $this->createQueryBuilder('project')
            ->leftJoin('project.awards', 'awards')
            ->addSelect('awards')
            ->where('project.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }

    public function getProjectWithParticipants(string $id): ?Project
    {
        return $this->createQueryBuilder('project')
            ->leftJoin(
                'project.participants',
                'participants',
            )
            ->addSelect('participants')
            ->leftJoin(
                'project.teams',
                'teams',
            )
            ->addSelect('teams')
            ->setParameter('acceptance', AcceptanceEnum::NO_DECISION->value)
            ->getQuery()
            ->getSingleResult();
    }

    public function eagerLoad(int|string $id): ?Project
    {
        return $this->eagerLoadBuilder()
            ->where('project.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }

    public function getAllProjectsQuery(): Query
    {
        $states = [
            ProjectStateEnum::UNDER_MODERATION->value,
            ProjectStateEnum::REJECTED->value,
        ];

        return $this->eagerLoadBuilder()
            ->where('project.state not in (:states)')
            ->setParameter('states', $states)
            ->getQuery();
    }

    public function getFestivalProjects(Festival $festival): ProjectCollection
    {
        /** @var Project[] $result */
        $result = $this->getFestivalProjectsQuery($festival)
            ->getResult();

        return new ProjectCollection($result);
    }

    public function getFestivalProjectsQuery(Festival $festival): Query
    {
        return $this->eagerLoadBuilder()
            ->where('festival = :festival')
            ->setParameter('festival', $festival)
            ->getQuery();
    }

    private function eagerLoadBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('project')
            ->leftJoin('project.author', 'author')
            ->addSelect('author')
            ->leftJoin('author.userEntity', 'user')
            ->addSelect('user')
            ->leftJoin('project.subjects', 'subjects')
            ->addSelect('subjects')
            ->leftJoin('project.orientedOn', 'orientedOn')
            ->addSelect('orientedOn')
            ->leftJoin('project.festival', 'festival')
            ->addSelect('festival')
            ->leftJoin('project.participants', 'participants')
            ->addSelect('participants')
            ->leftJoin('project.teams', 'teams')
            ->addSelect('teams');
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
