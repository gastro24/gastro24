<?php

namespace Gastro24\View\Helper;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * IsEmbeddableFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class IsEmbeddableFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $validator = $container->get( 'Gastro24\Validator\IframeEmbeddableUri' );
        $service = new IsEmbeddable($validator);

        return $service;
    }
}