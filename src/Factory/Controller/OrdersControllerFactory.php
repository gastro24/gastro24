<?php

namespace Gastro24\Factory\Controller;

use Gastro24\Controller\OrdersController;
use Gastro24\Entity\JobActivation;
use Interop\Container\ContainerInterface;

/**
 * OrdersControllerFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class OrdersControllerFactory
{
    public function __invoke( ContainerInterface $container, $requestedName, array $options = null )
    {
        $repositories = $container->get('repositories');
        $jobActivationRepository  = $repositories->get(JobActivation::class);
        $orderRepository = $repositories->get('Orders');
        $snaphotsRepository = $repositories->get('Jobs/JobSnapshot');
        $jobsRepository = $repositories->get('Jobs');

        return new OrdersController($jobActivationRepository, $orderRepository, $snaphotsRepository, $jobsRepository);
    }
}