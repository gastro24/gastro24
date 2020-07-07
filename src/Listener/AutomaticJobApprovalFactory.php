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

use Gastro24\Entity\JobActivation;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * Class AutomaticJobApprovalFactory
 *
 * @package Gastro24\Listener
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class AutomaticJobApprovalFactory implements FactoryInterface
{
    
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $repositories = $container->get('repositories');
        $snaphots     = $repositories->get('Jobs/JobSnapshot');
        $jobs         = $repositories->get('Jobs');
        $app          = $container->get('Application');
        $jobActivationRepository  = $repositories->get(JobActivation::class);
        $response     = $app->getResponse();
        $jobEvents = $container->get('Jobs/Events');
        $jobEvent = $container->get('Jobs/Event');
        $service      = new AutomaticJobApproval($jobs, $snaphots, $jobActivationRepository, $response, $jobEvents, $jobEvent);
        
        return $service;    
    }
}
