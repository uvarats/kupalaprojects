<?php

declare(strict_types=1);

namespace App\Feature\Project\Repository;

use App\Entity\Festival;
use App\Entity\Project;
use App\Entity\User;
use App\Enum\AcceptanceEnum;
use App\Enum\ProjectStateEnum;
use App\Feature\Project\Collection\ProjectCollection;
use App\Feature\Project\Collection\ProjectIdCollection;
use App\Feature\Project\Interface\ProjectRepositoryInterface;
use App\Feature\Project\ValueObject\ProjectId;
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
class ProjectRepository extends ServiceEntityRepository implements EagerLoadInterface, ProjectRepositoryInterface
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

    public function findActualProjects(): ProjectCollection
    {
        $qb = $this->createQueryBuilder('p');

        $states = [
            ProjectStateEnum::UNDER_MODERATION->value,
            ProjectStateEnum::REJECTED->value,
        ];

        $result = $qb
            ->innerJoin('p.festival', 'festival')
            ->where($qb->expr()->eq('festival.isActive', ':active'))
            ->andWhere($qb->expr()->notIn('p.state', ':states'))
            ->orderBy('p.dates.startsAt', 'ASC')
            ->addOrderBy('p.dates.endsAt', 'DESC')
            ->setParameter('active', true)
            ->setParameter('states', $states)
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();

        return new ProjectCollection($result);
    }

    public function eagerLoad(int|string $id): ?Project
    {
        return $this->eagerLoadBuilder('project')
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

        return $this->eagerLoadBuilder('project')
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
        return $this->eagerLoadBuilder('project')
            ->where('festival = :festival')
            ->setParameter('festival', $festival)
            ->getQuery();
    }

    private function eagerLoadBuilder(string $alias): QueryBuilder
    {
        return $this->createQueryBuilder($alias)
            ->select(
                'project',
                'author',
                'user',
                'subjects',
                'orientedOn',
                'festival',
                'participants',
                'participant',
                'teams',
                'team',
                'teamParticipants',
                'teamParticipant',
            )
            ->leftJoin('project.author', 'author')
            ->leftJoin('author.userEntity', 'user')
            ->leftJoin('project.subjects', 'subjects')
            ->leftJoin('project.orientedOn', 'orientedOn')
            ->leftJoin('project.festival', 'festival')
            ->leftJoin('project.participants', 'participants')
            ->leftJoin('participants.participant', 'participant')
            ->leftJoin('project.teams', 'teams')
            ->leftJoin('teams.team', 'team')
            ->leftJoin('team.teamParticipants', 'teamParticipants')
            ->leftJoin('teamParticipants.participant', 'teamParticipant');
    }

    public function createSearchQueryBuilder(string $alias): QueryBuilder
    {
        return $this->createQueryBuilder($alias)
            ->select(
                $alias,
                'author',
                'user',
                'subjects',
                'orientedOn',
                'festival',
                'participants',
                'participant',
                'teams',
                'team',
                'teamParticipants',
                'teamParticipant',
            )
            ->leftJoin($alias . '.author', 'author')
            ->leftJoin('author.userEntity', 'user')
            ->leftJoin($alias . '.subjects', 'subjects')
            ->leftJoin($alias . '.orientedOn', 'orientedOn')
            ->leftJoin($alias . '.festival', 'festival')
            ->leftJoin($alias . '.participants', 'participants')
            ->leftJoin('participants.participant', 'participant')
            ->leftJoin($alias . '.teams', 'teams')
            ->leftJoin('teams.team', 'team')
            ->leftJoin('team.teamParticipants', 'teamParticipants')
            ->leftJoin('teamParticipants.participant', 'teamParticipant');
    }

    public function findAllById(ProjectIdCollection $identifiers): ProjectCollection
    {
        $qb = $this->createQueryBuilder('p');

        $result = $qb->where($qb->expr()->in('p.id', ':identifiers'))
            ->setParameter('identifiers', $identifiers->toArray())
            ->getQuery()
            ->getResult();

        return new ProjectCollection($result);
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
    #[\Override]
    public function findOneById(ProjectId $projectId): ?Project
    {
        return $this->createQueryBuilder('p')
            ->where('p.id = :id')
            ->setParameter('id', $projectId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
