<?php

namespace Gastro24\Factory\Form;

use Auth\Options\CaptchaOptions;
use CompanyRegistration\Options\RegistrationFormOptions;
use Gastro24\Form\Filter\SimpleRegisterInputFilter;
use Gastro24\Form\SimpleRegisterForm;
use Gastro24\Form\SingleJobForm;
use Gastro24\Form\SingleJobHydrator;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * SingleJobFormFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class SingleJobFormFactory implements FactoryInterface
{

    public function __invoke( ContainerInterface $container, $requestedName, array $options = null )
    {
        /**
         * @var $filter SimpleRegisterInputFilter
         */
        //$filter = $container->get('Gastro24\Form\Filter\SimpleRegisterInputFilter');
        $hydrator = $container->get('HydratorManager')->get(SingleJobHydrator::class);

        /* @var $config CaptchaOptions */
        //$config = $container->get('Auth/CaptchaOptions');

        $formManager = $container->get('FormElementManager');
        $formOptions = $container->get(\Gastro24\Options\JobDetailsForm::class);

        /* @var $configForm RegistrationFormOptions */
        //$formOptions = $container->get('CompanyRegistration/RegistrationFormOptions');

        $form = new SingleJobForm($formManager, $formOptions, null);
        $form->setHydrator($hydrator);
        $form->setAttribute('id', 'single-job-form');
        //$form->setInputfilter($filter);

        return $form;
    }
}