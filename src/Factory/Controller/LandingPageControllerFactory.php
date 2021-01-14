<?php

namespace Gastro24\Factory\Controller;

use Gastro24\Controller\LandingPageController;
use Interop\Container\ContainerInterface;

class LandingPageControllerFactory
{

    public function __invoke( ContainerInterface $container, $requestedName, array $options = null )
    {
        $landingPageOptions = $container->get(\Gastro24\Options\Landingpages::class);

        return new LandingPageController($landingPageOptions);
    }
}