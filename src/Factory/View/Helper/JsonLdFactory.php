<?php
namespace Gastro24\Factory\View\Helper;

use Gastro24\View\Helper\JsonLd;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class JsonLdFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     *
     * @return JsonLd
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $repositories = $container->get('repositories');
        $orders       = $repositories->get('Orders');

        return new JsonLd($orders);
    }

}