<?php

namespace Gastro24\Factory\Form\Hydrator;

use Gastro24\Entity\OrganizationAdditional;
use Gastro24\Form\Organizations\Hydrator\OrganizationSocialHydrator;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class OrganizationSocialHydratorFactory implements FactoryInterface
{
    public function __invoke( ContainerInterface $container, $requestedName, array $options = null )
    {
        $organizationAdditionalRepository  = $container->get('repositories')->get(OrganizationAdditional::class);
        $hydrator = new OrganizationSocialHydrator($organizationAdditionalRepository);

        return $hydrator;
    }
}