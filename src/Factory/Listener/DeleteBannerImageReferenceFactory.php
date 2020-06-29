<?php

namespace Gastro24\Factory\Listener;

use Gastro24\Entity\OrganizationAdditional;
use Gastro24\Listener\DeleteBannerImageReference;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * DeleteBannerImageReferenceFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class DeleteBannerImageReferenceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $repository = $container->get('repositories')->get(OrganizationAdditional::class);
        $service = new DeleteBannerImageReference($repository);

        return $service;
    }
}