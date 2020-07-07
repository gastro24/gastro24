<?php

namespace Gastro24\Listener;

use Gastro24\Entity\JobActivation;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

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
        $jobTemplateHelper = $helpers->get('gastroJobTemplate');
        $embeddableHelper = $helpers->get('gastroJobTemplate');
        $logger = $container->get('Core/Log');
        $jobActivationRepository  = $container->get('repositories')->get(JobActivation::class);
        // path/to/module/test/sandbox/config/autoload directory
        $configPath = __DIR__.'/../../test/sandbox/config/autoload/';

        $service = new GoogleIndexApi($jobUrlHelper, $logger, $configPath, $jobTemplateHelper, $embeddableHelper, $jobActivationRepository);

        return $service;
    }
}