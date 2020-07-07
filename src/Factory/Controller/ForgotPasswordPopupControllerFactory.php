<?php

namespace Gastro24\Factory\Controller;

use Auth\Controller\ForgotPasswordController;
use Gastro24\Controller\ForgotPasswordPopupController;
use Gastro24\Form\ForgotPasswordPopup;
use Interop\Container\ContainerInterface;
use Laminas\Log\LoggerInterface;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\Mvc\Controller\ControllerManager;

/**
 * ForgotPasswordPopupControllerFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class ForgotPasswordPopupControllerFactory implements FactoryInterface
{
    /**
     * Create a ForgotPasswordController controller
     *
     * @param  ContainerInterface $container
     * @param  string             $requestedName
     * @param  null|array         $options
     *
     * @return ForgotPasswordPopupController
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /**
         * @var $form    \Gastro24\Form\ForgotPasswordPopup
         * @var $service \Auth\Service\ForgotPassword
         * @var $logger  LoggerInterface
         */
        $form = $container->get('Gastro24\Form\ForgotPasswordPopup');
        $service = $container->get('Auth\Service\ForgotPassword');
        $logger = $container->get('Core/Log');

        return new ForgotPasswordPopupController($form, $service, $logger);
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return ForgotPasswordPopupController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, ForgotPasswordPopupController::class);
    }
}