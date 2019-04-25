<?php

namespace Gastro24\View\Helper;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * JobCountFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class JobCountFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $paginators = $container->get('Core/PaginatorService');
        $paginator  = $paginators->get('Jobs/Board', ['Jobs_Board', [
            'q',
            'l',
            'd' => 10]
        ]);

        $service = new JobCount($paginator);

        return $service;
    }
}