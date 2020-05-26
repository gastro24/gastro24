<?php

namespace Gastro24\Factory\Form;

use Gastro24\Form\InvoiceAddressForm;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * InvoiceAddressFormFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class InvoiceAddressFormFactory implements FactoryInterface
{
    public function __invoke( ContainerInterface $container, $requestedName, array $options = null )
    {
        $form = new InvoiceAddressForm('single-job-address-form');
        $form->setAttribute('id', 'single-job-address-form');

        return $form;
    }
}