<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2018 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\Listener;

use Gastro24\Form\JobDetailsForm;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for \Gastro24\Listener\JobFileUpload
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test  
 */
class JobFileUploadFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $formElementManager = $container->get('forms');

        $repository = $container->get('repositories')->get('Gastro24/TemplateImage');
        $jobRepository = $container->get('repositories')->get('Jobs');

        $service = new JobFileUpload($formElementManager, $repository, $jobRepository);
        
        return $service;    
    }
}
