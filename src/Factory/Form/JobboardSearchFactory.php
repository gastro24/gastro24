<?php
namespace Gastro24\Factory\Form;

use Gastro24\Form\JobboardSearch;
use Interop\Container\ContainerInterface;
use \Jobs\Factory\Form\JobboardSearchFactory as BaseFactory;

/**
 * Class JobboardSearchFactory
 * @package Gastro24\Factory\Form
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class JobboardSearchFactory extends BaseFactory
{
    const OPTIONS_NAME = 'Jobs/JobboardSearchOptions';

    const CLASS_NAME = JobboardSearch::class;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $instance = parent::__invoke($container, $requestedName, $options);
        $moduleOptions      = $container->get('Solr/Options/Module');
        $solrConnectionString = 'http://' . $moduleOptions->getHostname() . ':' . $moduleOptions->getPort() . $moduleOptions->getJobsPath();
        $instance->setSolrConnectionString($solrConnectionString);

        $helpers = $container->get('ViewHelperManager');
        $serverUrl = $helpers->get('serverUrl');
        $basepath = $helpers->get('basepath');
        $mainPath = $serverUrl($basepath);
        $instance->setBasePath($mainPath);

        return $instance;
    }
}
