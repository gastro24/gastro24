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
        //TODO: read from config
        $organizationName = 'Hotel & Restaurant Chartreuse AG';
        $query->addParam('bq', 'organizationName:(' . $organizationName . ')^10');
        parent::createQuery($params, $query, $facets);
    }
}
