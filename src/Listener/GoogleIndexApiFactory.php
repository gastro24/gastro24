<?php

namespace Gastro24\Listener;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * GoogleIndexApi.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class GoogleIndexApiFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $helpers   = $container->get('ViewHelperManager');
        $jobUrlHelper = $helpers->get('jobUrl');
        $logger = $container->get('Core/Log');
        // path/to/module/test/sandbox/config/autoload directory
        $configPath = __DIR__.'/../../test/sandbox/config/autoload/';

        $service = new GoogleIndexApi($jobUrlHelper, $logger, $configPath);

        return $service;
    }
}