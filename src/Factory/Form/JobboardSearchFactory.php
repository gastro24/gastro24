<?php
namespace Gastro24\Factory\Form;

use Gastro24\Form\JobboardSearch;
use Interop\Container\ContainerInterface;
use \Jobs\Factory\Form\JobboardSearchFactory as BaseFactory;
use Solr\Options\ModuleOptions;

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
        $moduleOptions      = $container->get('Solr/Options/Module');
        $solrConnectionString = 'http://' . $moduleOptions->getHostname() . ':' . $moduleOptions->getPort() . $moduleOptions->getJobsPath();
        $controller = new JobboardSearch($solrConnectionString);

        return $controller;
    }
}
