<?php

namespace Gastro24\Factory\Form;

use Gastro24\Form\Organizations\Hydrator\OrganizationSocialHydrator;
use Gastro24\Form\Organizations\OrganizationsSocialFieldset;
use Interop\Container\ContainerInterface;

/**
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class OrganizationsSocialFieldsetFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $hydrator = $container->get('HydratorManager')->get(OrganizationSocialHydrator::class);
        $service = new OrganizationsSocialFieldset();
        $service->setHydrator($hydrator);

        return $service;
    }
}