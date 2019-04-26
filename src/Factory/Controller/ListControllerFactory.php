<?php

namespace Gastro24\Factory\Controller;

use Gastro24\Controller\ListController;
use Gastro24\Entity\Hydrator\OrderHydrator;
use Gastro24\Entity\JobActivation;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * ListControllerFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class ListControllerFactory implements FactoryInterface
{
    public function __invoke( ContainerInterface $container, $requestedName, array $options = null )
    {
        $repository  = $container->get('repositories')->get(JobActivation::class);

        return new ListController($repository);
    }
}