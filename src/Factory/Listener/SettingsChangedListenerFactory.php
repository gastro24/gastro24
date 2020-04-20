<?php

namespace Gastro24\Factory\Listener;

use Gastro24\Listener\SettingsChangedListener;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * SettingsChangedListenerFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class SettingsChangedListenerFactory implements FactoryInterface
{
    /**
    * Create a SettingsChangedListener listener
    *
    * @param  ContainerInterface $container
    * @param  string             $requestedName
    * @param  null|array         $options
    *
    * @return SettingsChangedListener
    */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var \Core\Mail\MailService $mailService */
        $mailService = $container->get('Core/MailService');

        /* @var \Core\Options\ModuleOptions $coreConfig */
        $coreConfig = $container->get('Core/Options');

        $auth = $container->get('AuthenticationService');

        $listener = new SettingsChangedListener($mailService, $coreConfig, $auth);

        return $listener;
    }

}