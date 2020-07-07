<?php

namespace Gastro24\View\Helper;

use Gastro24\Entity\JobActivation;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * HasAutomaticJobActivationFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class HasAutomaticJobActivationFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $jobActivationRepository  = $container->get('repositories')->get(JobActivation::class);
        $service = new HasAutomaticJobActivation($jobActivationRepository);

        return $service;
    }
}