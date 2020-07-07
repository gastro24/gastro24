<?php

namespace Gastro24\Entity\Hydrator;

use Gastro24\Entity\JobActivation;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * OrderHydratorFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class OrderHydratorFactory implements FactoryInterface
{
    public function __invoke( ContainerInterface $container, $requestedName, array $options = null )
    {
        $repository  = $container->get('repositories')->get(JobActivation::class);

        return new OrderHydrator($repository);
    }
}