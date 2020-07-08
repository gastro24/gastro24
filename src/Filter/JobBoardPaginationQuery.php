<?php
namespace Gastro24\Filter;

use Solr\Facets;
use \Solr\Filter\JobBoardPaginationQuery as BaseQuery;
use SolrDisMaxQuery;

class JobBoardPaginationQuery extends BaseQuery
{

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
        parent::createQuery($params, $query, $facets);
    }
}
