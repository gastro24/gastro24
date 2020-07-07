<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2017 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\Factory\Controller;

use Gastro24\Controller\WordpressPageController;
use Gastro24\WordpressApi\Service\WordpressClient;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for \Gastro24\Controller\WordpressPageController
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @author Anthonius Munthi <me@itstoni.com>
 * @todo write test  
 */
class WordpressPageControllerFactory implements FactoryInterface
{
    
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $client = $container->get(WordpressClient::class);
        $client = $client->plugin('wp');

        return new WordpressPageController($client);
    }
}
