<?php

namespace Gastro24\Factory\Form\Hydrator;

use Gastro24\Entity\OrganizationAdditional;
use Gastro24\Entity\TemplateImage;
use Gastro24\Form\Organizations\Hydrator\OrganizationBannerHydrator;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class OrganizationBannerHydratorFactory implements FactoryInterface //extends ImageSetHydratorFactory //implements FactoryInterface
{
    public function __invoke( ContainerInterface $container, $requestedName, array $options = null )
    {
        $repositories = $container->get('repositories');
        $organizationAdditionalRepository  = $repositories->get(OrganizationAdditional::class);
        $templateImagesRepository  = $repositories->get(TemplateImage::class);
        $hydrator = new OrganizationBannerHydrator($organizationAdditionalRepository, $templateImagesRepository);

        return $hydrator;
    }
}