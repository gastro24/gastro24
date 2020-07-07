<?php
namespace Gastro24\Factory\Form;

use Auth\Form\LoginInputFilter;
use Gastro24\Form\Login;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Interop\Container\Exception\ContainerException;

/**
 * LoginFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class LoginFactory implements FactoryInterface
{
    /**
     * Create a Login form
     *
     * @param  ContainerInterface $container
     * @param  string             $requestedName
     * @param  null|array         $options
     *
     * @return Login
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var LoginInputFilter $filter */
        $filter = $container->get('Auth\Form\LoginInputFilter');
        $form = new Login(null, array());
        $form->setInputfilter($filter);
        return $form;
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return Login
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, Login::class);
    }
}