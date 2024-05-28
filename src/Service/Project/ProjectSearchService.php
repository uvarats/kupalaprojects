<?php

declare(strict_types=1);

namespace App\Service\Project;

use App\Dto\ProjectQuery;
use Elastica\Query;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use Pagerfanta\Pagerfanta;

final readonly class ProjectSearchService
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

    private function make(ProjectQuery $query): Query\BoolQuery
    {
        $searchQuery = new Query\BoolQuery();

        if ($query->isEmptyQuery()) {
            $searchQuery->addMust(new Query\MatchAll());

            return $searchQuery;
        }

        $this->applyQueryPrompt($searchQuery, $query);
        $this->addFestivalFilter($searchQuery, $query);
        $this->addDatesFilter($searchQuery, $query);
        $this->addSubjectFilter($searchQuery, $query);
        $this->addOrientedOnFilter($searchQuery, $query);

        return $searchQuery;
    }

    private function applyQueryPrompt(Query\BoolQuery $searchQuery, ProjectQuery $query): void
    {
        $queryString = $query->getQuery();
        if ($queryString !== null) {
            $multiMatch = new Query\MultiMatch();
            $multiMatch->setQuery($queryString);
            $multiMatch->setFields(['name', 'goal']);

            $searchQuery->addMust($multiMatch);
        }
    }

    private function addFestivalFilter(Query\BoolQuery $searchQuery, ProjectQuery $query): void
    {
        $festival = $query->getFestival();

        if ($festival !== null) {
            $nested = new Query\Nested();
            $nested->setPath('festival');

            $festivalIdQuery = new Query\BoolQuery();
            $matchId = new Query\MatchQuery('festival.id', $festival->getId()->toRfc4122());
            $festivalIdQuery->addMust($matchId);

            $nested->setQuery($festivalIdQuery);

            $searchQuery->addMust($nested);
        }
    }

    private function addDatesFilter(Query\BoolQuery $searchQuery, ProjectQuery $query): void
    {
        $dateFrom = $query->getDateFrom();
        if ($dateFrom !== null) {
            $fromFilter = new Query\Range('startsAt', [
                'gte' => $dateFrom->format('Y-m-d'),
            ]);

            $searchQuery->addMust($fromFilter);
        }

        $dateTo = $query->getDateTo();
        if ($dateTo !== null) {
            $toFilter = new Query\Range('endsAt', [
                'lte' => $dateTo->format('Y-m-d'),
            ]);

            $searchQuery->addMust($toFilter);
        }
    }

    private function addSubjectFilter(Query\BoolQuery $searchQuery, ProjectQuery $query): void
    {
        $subjects = $query->getSubjects();

        if (empty($subjects)) {
            return;
        }

        $subjectsQuery = new Query\Nested();
        $subjectsQuery->setPath('subjects');

        $subjectsIdClause = new Query\BoolQuery();

        foreach ($subjects as $subject) {
            $matchQuery = new Query\MatchQuery('subjects.id', $subject->getId()->toRfc4122());
            $subjectsIdClause->addShould($matchQuery);
        }

        $subjectsQuery->setQuery($subjectsIdClause);
        $searchQuery->addMust($subjectsQuery);
    }

    public function addOrientedOnFilter(Query\BoolQuery $searchQuery, ProjectQuery $query): void
    {
        $orientedOn = $query->getOrientedOn();

        if (empty($orientedOn)) {
            return;
        }

        $nestedQuery = new Query\Nested();
        $nestedQuery->setPath('orientedOn');

        $orientedOnClause = new Query\BoolQuery();

        foreach ($orientedOn as $educationSubGroup) {
            $match = new Query\MatchQuery('orientedOn.id', $educationSubGroup->getId()->toRfc4122());
            $orientedOnClause->addShould($match);
        }

        $nestedQuery->setQuery($orientedOnClause);
        $searchQuery->addMust($nestedQuery);
    }
}
