<?php

namespace Gastro24\Factory\Form;

use Auth\Form\ForgotPasswordInputFilter;
use Gastro24\Form\ForgotPasswordPopup;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * ForgotPasswordPopupFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class ForgotPasswordPopupFactory implements FactoryInterface
{
    /**
     * Create a ForgotPassword form
     *
     * @param  ContainerInterface $container
     * @param  string             $requestedName
     * @param  null|array         $options
     *
     * @return ForgotPasswordPopup
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var ForgotPasswordInputFilter $filter */
        $filter = $container->get('Auth\Form\ForgotPasswordInputFilter');

        $form = new ForgotPasswordPopup();
        $form->setInputfilter($filter);

        return $form;
    }
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return ForgotPasswordPopup
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, ForgotPasswordPopup::class);
    }
}