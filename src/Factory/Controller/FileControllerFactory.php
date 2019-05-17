<?php

namespace Gastro24\Factory\Controller;

use Gastro24\Controller\FileController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * FileControllerFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class FileControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $repositories = $container->get('repositories');
        $coreFileEvents = $container->get('Core/File/Events');

        return new FileController($repositories,$coreFileEvents);
    }
}