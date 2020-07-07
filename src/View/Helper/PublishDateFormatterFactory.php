<?php


namespace Gastro24\View\Helper;


use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class PublishDateFormatterFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $repositories = $container->get('repositories');
        $jobsRepository = $repositories->get('Jobs');

        return new PublishDateFormatter($jobsRepository);
    }
}