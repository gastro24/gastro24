<?php
namespace Gastro24\Factory\Controller\Organizations;

use Gastro24\Controller\Organizations\IndexController;
use Gastro24\Form\Organizations\Organizations;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory implements FactoryInterface
{
    /**
     * Create a IndexController controller
     *
     * @param  ContainerInterface $container
     * @param  string             $requestedName
     * @param  null|array         $options
     *
     * @return IndexController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $organizationRepository = $container->get('repositories')->get('Organizations/Organization');
        $form = new Organizations();
        $formManager = $container->get('FormElementManager');
        $viewHelper = $container->get('ViewHelperManager');
        $translator = $container->get('translator');

        return new IndexController($form, $organizationRepository, $translator, $formManager, $viewHelper);
    }
}