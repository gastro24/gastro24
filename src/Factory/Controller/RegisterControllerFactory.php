<?php

namespace Gastro24\Factory\Controller;

use CompanyRegistration\Options\RegisterControllerOptions;
use Gastro24\Controller\RegisterController;
use Interop\Container\ContainerInterface;

/**
 * RegisterControllerFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class RegisterControllerFactory
{
    public function __invoke( ContainerInterface $container, $requestedName, array $options = null )
    {
        $options = new RegisterControllerOptions(array());

        $repositories = $container->get('repositories');
        $authRegisterService = $container->get('Gastro24\Service\Register');
        $logger = $container->get('Core/Log');
        $formManager = $container->get('FormElementManager');
        return new RegisterController(
            $options,
            $repositories,
            $authRegisterService,
            $logger,
            $formManager
        );
    }
}