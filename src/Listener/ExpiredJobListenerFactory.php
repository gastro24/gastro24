<?php

namespace Gastro24\Listener;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

/**
 * ExpiredJobListenerFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class ExpiredJobListenerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $repositories = $container->get('repositories');
        $mailer = $container->get('Core/MailService');
        $service = new ExpiredJobListener($repositories, $mailer);

        return $service;
    }
}