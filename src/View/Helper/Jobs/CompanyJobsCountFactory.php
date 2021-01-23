<?php

namespace Gastro24\View\Helper\Jobs;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * SimilarJobsFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class CompanyJobsCountFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $paginators = $container->get('Core/PaginatorService');
        $service = new CompanyJobsCount($paginators);

        return $service;
    }
}