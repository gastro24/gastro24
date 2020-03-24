<?php

namespace Gastro24\View\Helper;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

/**
 * JobDraftsCountFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class JobDraftsCountFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $jobsRepository  = $container->get('repositories')->get('Jobs');

        return new JobDraftsCount($jobsRepository);
    }
}