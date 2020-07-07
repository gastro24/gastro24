<?php

namespace Gastro24\View\Helper;

use Gastro24\Entity\JobActivation;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * ShowAutomaticJobActivationHintFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class ShowAutomaticJobActivationHintFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $jobActivationRepository  = $container->get('repositories')->get(JobActivation::class);
        $service = new ShowAutomaticJobActivationHint($jobActivationRepository);

        return $service;
    }
}