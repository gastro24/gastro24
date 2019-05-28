<?php

namespace Gastro24\Paginator;

use Solr\Options\ModuleOptions;
use Solr\Paginator\PaginatorFactoryAbstract;

/**
 * JobsSimilarPaginatorFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class JobsSimilarPaginatorFactory extends PaginatorFactoryAbstract
{
    protected function getFilter()
    {
        return 'Gastro24/Paginator/JobsSimilarPaginationQuery';
    }

    /**
     * {@inheritdoc}
     */
    protected function getConnectPath(ModuleOptions $options)
    {
        return $options->getJobsPath();
    }
}