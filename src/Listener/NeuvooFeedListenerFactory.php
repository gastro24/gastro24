<?php

namespace Gastro24\Listener;

use Gastro24\Entity\JobActivation;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * Class NeuvooFeedListenerFactory
 * @package Gastro24\Listener
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class NeuvooFeedListenerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $repositories = $container->get('repositories');
        $jobRepository = $repositories->get('Jobs');
        $service      = new NeuvooFeedListener($jobRepository);

        return $service;
    }
}