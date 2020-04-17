<?php

namespace Gastro24\Factory\Controller;

use Auth\Repository\User;
use Gastro24\Controller\RegisterConfirmationController;
use Interop\Container\ContainerInterface;
use Zend\Log\LoggerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * RegisterConfirmationControllerFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class RegisterConfirmationControllerFactory implements FactoryInterface
{
    /**
     * Create a RegisterConfirmationController controller
     *
     * @param  ContainerInterface $container
     * @param  string             $requestedName
     * @param  null|array         $options
     *
     * @return RegisterConfirmationController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /**
         * @var $service \Gastro24\Service\RegisterConfirmation
         * @var $logger  LoggerInterface
         */
        $registerConfirmationService = $container->get('Gastro24\Service\RegisterConfirmation');
        $registerService = $container->get('Gastro24\Service\Register');
        $logger = $container->get('Core/Log');
        /**
         * @var User $userRepository
         */
        $repositories = $container->get('repositories');
        $userRepository = $container->get('repositories')->get('Auth/User');

        $formManager = $container->get('FormElementManager');

        return new RegisterConfirmationController($registerConfirmationService, $registerService, $logger, $formManager, $repositories);
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return RegisterConfirmationController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, RegisterConfirmationController::class);
    }
}