<?php
namespace Gastro24\Filter;

use Gastro24\Options\TopJobsOptions;
use Solr\Facets;
use \Solr\Filter\JobBoardPaginationQuery as BaseQuery;
use Solr\Options\ModuleOptions;
use SolrDisMaxQuery;

class JobBoardPaginationQuery extends BaseQuery
{
    protected $topJobsOptions;

    /**
     * @param $solrOptions
     * @param $topJobsOptions
     */
    public function __construct(ModuleOptions $solrOptions, TopJobsOptions $topJobsOptions)
    {
        $this->topJobsOptions = $topJobsOptions;
        parent::__construct($solrOptions);
    }

    /**
     * @inheritdoc
     */
    public function createQuery(array $params, SolrDisMaxQuery $query, Facets $facets)
    {
        // show only jobs, where publishDate not in future
        $query->addFilterQuery('datePublishStart:[* TO NOW]');

        $query->addFacetQuery('datePublishStart:[NOW-1DAY TO NOW]') // 24h
            ->addFacetQuery('datePublishStart:[NOW-3DAYS TO NOW]') // 3 Tage
            ->addFacetQuery('datePublishStart:[NOW-7DAYS TO NOW]') // 7 Tage
            ->addFacetQuery('datePublishStart:[NOW-30DAYS TO NOW]'); // 30 Tage

        foreach($this->topJobsOptions->getOrganizations() as $organizationName => $boostParam) {
            $query->addBoostQuery('organizationName', '"' . $organizationName . '"', $boostParam);
        }

        foreach($this->topJobsOptions->getJobs() as $jobId => $boostParam) {
            $query->addBoostQuery('id', '"' . $jobId . '"', $boostParam);
        }

        parent::createQuery($params, $query, $facets);
    }
}
