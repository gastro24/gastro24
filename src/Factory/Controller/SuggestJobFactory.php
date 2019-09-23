<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2018 Cross Solution <http://cross-solution.de>
 */

/** */
namespace Gastro24\Factory\Controller;

use Gastro24\Controller\SuggestJobs;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Solr\Options\ModuleOptions;

/**
 * Factory for \Gastro24\Controller\SuggestJob
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test
 */
class SuggestJobFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $moduleOptions      = $container->get('Solr/Options/Module');
//        $connectPath        = $this->getConnectPath($moduleOptions);
//        $solrClient = $container->get('Solr/Manager')->getClient($connectPath);
        $controller = new SuggestJobs($moduleOptions);

        return $controller;
    }

    /**
     * {@inheritdoc}
     */
    protected function getConnectPath(ModuleOptions $options)
    {
        return $options->getJobsPath();
    }
}
