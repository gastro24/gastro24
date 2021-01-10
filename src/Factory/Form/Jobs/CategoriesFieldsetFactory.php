<?php

namespace Gastro24\Factory\Form\Jobs;

use Gastro24\Entity\Hydrator\JobCategoryHydrator;
use Gastro24\Form\Jobs\CategoriesFieldset;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * Class CategoriesFieldsetFactory
 * @package Gastro24\Factory\Form\Jobs
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class CategoriesFieldsetFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $hydrator = $container->get('HydratorManager')->get(JobCategoryHydrator::class);
        $landingPageOptions = $container->get(\Gastro24\Options\Landingpages::class);

        $service = new CategoriesFieldset($landingPageOptions, 'categories');
        $service->setHydrator($hydrator);

        return $service;
    }
}