<?php

namespace Gastro24\Factory\Form\Hydrator;

use Gastro24\Entity\OrganizationAdditional;
use Gastro24\Form\Organizations\Hydrator\OrganizationDescriptionHydrator;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * OrganizationDescriptionHydratorFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class OrganizationDescriptionHydratorFactory implements FactoryInterface
{
    public function __invoke( ContainerInterface $container, $requestedName, array $options = null )
    {
        $organizationAdditionalRepository  = $container->get('repositories')->get(OrganizationAdditional::class);
        $hydrator = new OrganizationDescriptionHydrator($organizationAdditionalRepository);

        return $hydrator;
    }
}