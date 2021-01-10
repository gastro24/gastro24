<?php

namespace Gastro24\Factory\Filter;

use Gastro24\Filter\Solr\JobEntityToSolrDocument as EntityToDocumentFilter;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Solr\Listener\JobEventSubscriber;

class JobEventSubscriberFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @return JobEventSubscriber
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $options = $container->get('Solr/Options/Module');
        $landingPageOptions = $container->get(\Gastro24\Options\Landingpages::class);

        return new JobEventSubscriber($container->get('Solr/Manager'), new EntityToDocumentFilter($options, $landingPageOptions));
    }
}