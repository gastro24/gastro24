<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2017 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\WordpressApi\Factory\View\Helper;

use Gastro24\WordpressApi\Filter\PageIdMap;
use Gastro24\WordpressApi\Service\WordpressClient;
use Gastro24\WordpressApi\View\Helper\WordpressContent;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @author Anthonius Munthi <me@itstoni.com>
 * @todo write test 
 */
class WordpressContentFactory implements FactoryInterface
{
	/**
	 * @param ContainerInterface $container
	 * @param string $requestedName
	 * @param array|null $options
	 *
	 * @return WordpressContent
	 */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $client = $container->get(WordpressClient::class);
        $idMap  = $container->get('FilterManager')->get(PageIdMap::class);

        $helper = new WordpressContent($client, $idMap);

        return $helper;
    }
}