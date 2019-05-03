<?php

namespace Gastro24\View\Helper;

use Gastro24\Entity\Hydrator\OrderHydrator;
use Gastro24\Entity\JobActivation;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * HydrateOrderObjectFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class HydrateOrderObjectFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $jobActivationRepository  = $container->get('repositories')->get(JobActivation::class);
        $hydrator = new OrderHydrator($jobActivationRepository);
        $service = new HydrateOrderObject($hydrator);

        return $service;
    }
}