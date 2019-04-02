<?php

namespace Gastro24\Factory\Form;

use Auth\Form\RegisterInputFilter;
use Auth\Options\CaptchaOptions;
use CompanyRegistration\Form\Register;
use CompanyRegistration\Options\RegistrationFormOptions;
use Gastro24\Form\CompanyRegisterForm;
use Interop\Container\ContainerInterface;

/**
 * RegisterFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class RegisterFactory extends \CompanyRegistration\Factory\Form\RegisterFactory
{

    public function __invoke( ContainerInterface $container, $requestedName, array $options = null )
    {
        /**
         * @var $filter RegisterInputFilter
         */
        $filter = $container->get('Auth\Form\RegisterInputFilter');

        /* @var $config CaptchaOptions */
        $config = $container->get('Auth/CaptchaOptions');

        $formManager = $container->get('FormElementManager');

        /* @var $configForm RegistrationFormOptions */
        $formOptions = $container->get('CompanyRegistration/RegistrationFormOptions');

        $form = new CompanyRegisterForm($formManager, null, $config, $formOptions, $this->role);

        $form->setAttribute('id', 'registration');

        $form->setInputfilter($filter);

        return $form;
    }
}