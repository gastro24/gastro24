<?php

namespace Gastro24\Listener;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * DeleteJobFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class DeleteJobFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $acl = $container->get('Acl');
        $auth = $container->get('AuthenticationService');
        $user = $auth->getUser();
        $repositories = $container->get('repositories');
        $repository = $repositories->get('Jobs');
        $listener = new DeleteJob($repository, $user, $acl);

        return $listener;
    }

}