<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2018 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\Controller;

use Gastro24\Options\CompanyTemplatesMap;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for \Gastro24\Controller\RedirectExternalJobs
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test  
 */
class RedirectExternalJobsFactory implements FactoryInterface
{
    
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $manager = $container->get('Solr/Manager');
        $solrClient = $manager->getClient($manager->getOptions()->getJobsPath());
        $validators = $container->get('ValidatorManager');
        $validator  = $validators->get(\Gastro24\Validator\IframeEmbeddableUri::class);
        $templatesMap = $container->get(CompanyTemplatesMap::class);

        $helpers = $container->get('ViewHelperManager');
        $serverUrl = $helpers->get('serverUrl');
        $basepath = $helpers->get('basepath');
        $path = $serverUrl($basepath()) . '/';
        $translator = $container->get('translator');

        $service    = new RedirectExternalJobs($validator, $templatesMap, $solrClient, $translator, $path);
        
        return $service;    
    }
}
