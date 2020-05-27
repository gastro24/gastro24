<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2018 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\Factory\Controller;

use Gastro24\Controller\CreateSingleJobController;
use Gastro24\Form\InvoiceAddressForm;
use Gastro24\Form\SingleJobForm;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for \Gastro24\Controller\CreateSingleJobController
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test  
 */
class CreateSingleJobFactory implements FactoryInterface
{
    
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $forms = $container->get('forms');
        $form  = $forms->get(SingleJobForm::class);
        $invoiceAddressForm  = $forms->get(InvoiceAddressForm::class);
        $controller = new CreateSingleJobController($form, $invoiceAddressForm);

        return $controller;
    }
}
