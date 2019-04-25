<?php

namespace Gastro24\Factory\Controller;

use CompanyRegistration\Options\RegisterControllerOptions;
use Gastro24\Controller\OrdersController;
use Gastro24\Controller\RegisterController;
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
        $jobActivationRepository  = $container->get('repositories')->get(JobActivation::class);

        return new OrdersController($jobActivationRepository);
    }
}