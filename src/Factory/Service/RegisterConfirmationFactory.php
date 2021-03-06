<?php

namespace Gastro24\Factory\Service;

use Gastro24\Service\RegisterConfirmation;
use Auth\Repository;
use Interop\Container\ContainerInterface;
use Laminas\Authentication\AuthenticationService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Interop\Container\Exception\ContainerException;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;

/**
 * RegisterFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class RegisterConfirmationFactory implements FactoryInterface
{
    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string             $requestedName
     * @param  null|array         $options
     *
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /**
         * @var \Auth\Repository\User $userRepository
         */
        $userRepository = $container->get('repositories')->get('Auth/User');
        $authenticationService = new AuthenticationService();

        $service = new RegisterConfirmation($userRepository, $authenticationService);

        $events = $container->get('Auth/Events');
        $service->setEventManager($events);

        return $service;
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return RegisterConfirmation
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, RegisterConfirmation::class);
    }
}