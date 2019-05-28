<?php

namespace Gastro24\Paginator;

use Interop\Container\ContainerInterface;
use Solr\Filter\JobBoardPaginationQuery;
use Solr\Options\ModuleOptions;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * JobsSimilarPaginationQueryFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class JobsSimilarPaginationQueryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var ModuleOptions $options */
        $options = $container->get('Solr/Options/Module');

        $filter = new JobsSimilarPaginationQuery($options);
        return $filter;
    }

    /**
     * @param ContainerInterface $container
     * @return JobBoardPaginationQuery|mixed
     */
    public function createService(ContainerInterface $container)
    {
        return $this($container, JobsSimilarPaginationQuery::class);
    }
}