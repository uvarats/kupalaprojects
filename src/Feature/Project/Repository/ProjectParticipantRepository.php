<?php

declare(strict_types=1);

namespace App\Feature\Project\Repository;

use App\Entity\Project;
use App\Entity\ProjectParticipant;
use App\Entity\User;
use App\Enum\AcceptanceEnum;
use App\Feature\Project\Collection\ProjectParticipantCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use function Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<ProjectParticipant>
 *
 * @method ProjectParticipant|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectParticipant|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectParticipant[]    findAll()
 * @method ProjectParticipant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectParticipant::class);
    }

    public function searchParticipants(Project $project, string $query = ''): ProjectParticipantCollection
    {
        $qb = $this->createQueryBuilder('pp');

        $qb->select('pp')
            ->where('pp.project = :project')
            ->setParameter('project', $project);

        if (!empty($query)) {
            $qb->leftJoin('pp.participant', 'p')
                ->addSelect('p');

            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like(
                        'CONCAT(p.lastName, \' \', p.firstName, \'\', p.middleName)',
                        $qb->expr()->literal('%' . $query . '%'),
                    ),
                    $qb->expr()->like(
                        'p.email',
                        $qb->expr()->literal('%' . $query . '%'),
                    ),
                ),
            );
        }

        //dd($qb->getDQL());

        $result = $qb->getQuery()
            ->getResult();

        return new ProjectParticipantCollection($result);
    }

    public function findAllForUser(User $user): ProjectParticipantCollection
    {
        $qb = $this->createQueryBuilder('pp')
            ->select('pp', 'participant', 'project')
            ->leftJoin('pp.participant', 'participant')
            ->leftJoin('pp.project', 'project')
            ->where('participant.account = :user')
            ->setParameter('user', $user);

        $result = $qb
            ->getQuery()
            ->getResult();

        return new ProjectParticipantCollection($result);
    }

    public function findAllApproved(Project $project): ProjectParticipantCollection
    {
        $qb = $this->createQueryBuilder('pp');

        $qb->select('pp', 'project')
            ->leftJoin('pp.project', 'project')
            ->where('project = :project')
            ->andWhere('pp.acceptance = :acceptance')
            ->setParameter('project', $project)
            ->setParameter('acceptance', AcceptanceEnum::APPROVED);

        $result = $qb->getQuery()
            ->getResult();

        return new ProjectParticipantCollection($result);
    }
}
