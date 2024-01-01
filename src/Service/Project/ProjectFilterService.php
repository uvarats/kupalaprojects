<?php

declare(strict_types=1);

namespace App\Service\Project;

use App\Dto\ProjectQuery;
use Elastica\Query;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use Pagerfanta\Pagerfanta;

final readonly class ProjectFilterService
{
    public function __construct(
        private PaginatedFinderInterface $finder,
    ) {}

    public function findPaginated(
        ProjectQuery $query,
        int $maxPerPage,
        int $currentPage,
    ): Pagerfanta {
        $elasticQuery = $this->make($query);

        $pager = $this->finder->findPaginated($elasticQuery);

        $pager->setMaxPerPage($maxPerPage)
            ->setCurrentPage($currentPage);

        return $pager;
    }

    // todo: separated interfaced factory
    private function make(ProjectQuery $query): Query\BoolQuery
    {
        $searchQuery = new Query\BoolQuery();

        if ($query->isEmptyQuery()) {
            $searchQuery->addMust(new Query\MatchAll());

            return $searchQuery;
        }

        $queryString = $query->getQuery();
        if ($queryString !== null) {
            $multiMatch = new Query\MultiMatch();
            $multiMatch->setQuery($queryString);
            $multiMatch->setFields(['name', 'goal']);

            $searchQuery->addMust($multiMatch);
        }

        $festival = $query->getFestival();

        if ($festival !== null) {
            $nested = new Query\Nested();
            $nested->setPath('festival');

            $festivalIdQuery = new Query\BoolQuery();
            $matchId = new Query\MatchQuery('festival._id', $festival->getId()->toRfc4122());
            $festivalIdQuery->addMust($matchId);

            $nested->setQuery($festivalIdQuery);

            $searchQuery->addMust($nested);
        }

        return $searchQuery;
    }
}
