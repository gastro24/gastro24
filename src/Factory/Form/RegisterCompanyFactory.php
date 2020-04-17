<?php

namespace Gastro24\Factory\Form;

use Auth\Options\CaptchaOptions;
use CompanyRegistration\Options\RegistrationFormOptions;
use Gastro24\Form\Filter\SimpleRegisterInputFilter;
use Gastro24\Form\RegisterCompanyForm;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * SimpleRegisterFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class RegisterCompanyFactory implements FactoryInterface
{

    public function __invoke( ContainerInterface $container, $requestedName, array $options = null )
    {
        /**
         * @var $filter SimpleRegisterInputFilter
         */
        //$filter = $container->get('Gastro24\Form\Filter\SimpleRegisterInputFilter');

        /* @var $config CaptchaOptions */
        //$config = $container->get('Auth/CaptchaOptions');

        $formManager = $container->get('FormElementManager');

        /* @var $configForm RegistrationFormOptions */
        //$formOptions = $container->get('CompanyRegistration/RegistrationFormOptions');

        $form = new RegisterCompanyForm($formManager, null);
        $form->setAttribute('id', 'registration-company-form');
        //$form->setInputfilter($filter);

        return $form;
    }
}