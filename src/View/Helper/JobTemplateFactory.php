<?php

namespace Gastro24\View\Helper;

use Gastro24\Options\CompanyTemplatesMap;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * JobTemplateFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class JobTemplateFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $templatesMap = $container->get( 'Gastro24\Options\CompanyTemplatesMap' );
        $service = new JobTemplate($templatesMap);

        return $service;
    }
}