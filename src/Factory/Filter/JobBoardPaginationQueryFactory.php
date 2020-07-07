<?php

namespace Gastro24\Factory\Filter;

use Gastro24\Filter\JobBoardPaginationQuery;
use Interop\Container\ContainerInterface;
use \Solr\Factory\Filter\JobBoardPaginationQueryFactory as BaseFactory;
use Solr\Options\ModuleOptions;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * JobBoardPaginationQueryFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class JobBoardPaginationQueryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var ModuleOptions $options */
        $options = $container->get('Solr/Options/Module');

        $filter = new JobBoardPaginationQuery($options);
        return $filter;
    }

    /**
     * @param ContainerInterface $container
     * @return JobBoardPaginationQuery|mixed
     */
    public function createService(ContainerInterface $container)
    {
        return $this($container, JobBoardPaginationQuery::class);
    }
}