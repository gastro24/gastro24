<?php

namespace Gastro24\Factory\Form;

use Gastro24\Form\Organizations\Hydrator\OrganizationDescriptionHydrator;
use Gastro24\Form\Organizations\OrganizationsDescriptionFieldset;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class OrganizationsDescriptionFieldsetFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $hydrator = $container->get('HydratorManager')->get(OrganizationDescriptionHydrator::class);
        $service = new OrganizationsDescriptionFieldset();
        $service->setHydrator($hydrator);

        return $service;
    }

}