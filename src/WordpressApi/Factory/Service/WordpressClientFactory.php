<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2017 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\WordpressApi\Factory\Service;

use Gastro24\WordpressApi\Service\WordpressClient;
use Gastro24\WordpressApi\Service\WordpressClientPluginManager;
use Interop\Container\ContainerInterface;
use Laminas\Cache\StorageFactory;
use Laminas\Http\Client;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class WordpressClientFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $options       = $container->get('Gastro24/WordpressApiOptions');
        $pluginsConfig = $container->get('Config');
        $pluginsConfig = isset($pluginsConfig['wordpressclient_plugins']) ? $pluginsConfig['wordpressclient_plugins'] : [];
        $plugins       = new WordpressClientPluginManager($container, $pluginsConfig);
        $client        = new WordpressClient($options->getBaseUrl(), $plugins);

        $plugins->setClient($client);

        if ($container->has('Gastro24/WordpressApiClient')) {
            $client->setHttpClient($container->get('Gastro24/WordpressApiClient'));
        } else if ($clientOptions = $options->getHttpClientOptions()) {
            $httpClient = $this->setupHttpClient($clientOptions);
            $client->setHttpClient($httpClient);
        }

        if ($container->has('Gastro24/WordpressApiCache')) {
            $client->setCache($container->get('Gastro24/WordpressApiCache'));
        } else if ($cacheOptions = $options->getCacheOptions()) {
            $cache = StorageFactory::factory($cacheOptions);
            $client->setCache($cache);
        }

        return $client;
    }

    private function setupHttpClient($options)
    {
        $client = new Client();

        foreach ($options as $method => $args) {
            if (is_int($method)) {
                $method = $args;
                $args   = [];
            }

            $callback = [$client, "set$method"];

            if (is_callable($callback)) {
                call_user_func_array($callback, $args);
            }
        }

        return $client;
    }
}