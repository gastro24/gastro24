<?php

namespace Gastro24\Form;

use Auth\Entity\User;
use Auth\Options\CaptchaOptions;
use CompanyRegistration\Form\Register;
use CompanyRegistration\Options\RegistrationFormOptions;
use Core\Form\ButtonsFieldset;
use Orders\Entity\InvoiceAddress;
use Orders\Entity\Order;
use Orders\Entity\SettingsContainer;
use Settings\Repository\Event\InjectSettingsEntityResolverListener;
use Zend\Captcha\Image;
use Zend\Captcha\ReCaptcha;
use Zend\Form\Fieldset;
use Zend\Form\FormElementManager\FormElementManagerV3Polyfill as FormElementManager;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * CompanyRegisterForm.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class CompanyRegisterForm extends Register
{
    /**
     * @var FormElementManager
     */
    protected $formManager;

    public function __construct(FormElementManager $formManager, $name = 'register-form', CaptchaOptions $options, RegistrationFormOptions $formOptions, $role = User::ROLE_RECRUITER)
    {
        parent::__construct($name, $options, $formOptions, $role);

        $this->setAttribute('data-handle-by', 'native');
        $this->setAttribute('class', 'form-horizontal');

        $fieldset = new Fieldset('register');
        $fieldset->setOptions(array('renderFieldset' => true));

        if ($formOptions->isEnabled(RegistrationFormOptions::FIELD_GENDER)) {
            $fieldset->add(
                [
                    'name'       => RegistrationFormOptions::FIELD_GENDER,
                    'type'       => 'Zend\Form\Element\Select',
                    'options'    => [
                        'label'         => /*@translate */ 'Salutation',
                        'value_options' => [
                            ''       => '', // => /*@translate */ 'please select',
                            'male'   => /*@translate */ 'Mr.',
                            'female' => /*@translate */ 'Mrs.',
                        ]
                    ],
                    'attributes' => [
                        'data-placeholder' => /*@translate*/ 'please select',
                        'data-allowclear'  => 'false',
                        'data-searchbox'   => -1,
                        'required'         => $formOptions->isRequired(RegistrationFormOptions::FIELD_GENDER)
                    ],
                ]
            );
        }


        if ($formOptions->isEnabled(RegistrationFormOptions::FIELD_NAME)){
            $fieldset->add(
                array(
                    'type'       => 'text',
                    'name'       => RegistrationFormOptions::FIELD_NAME,
                    'options'    => array(
                        'label' => /*@translate*/ 'Name',
                    ),
                    'attributes' => [
                        'required' => $formOptions->isRequired(RegistrationFormOptions::FIELD_NAME)
                    ],
                )
            );
        }

        if ($formOptions->isEnabled(RegistrationFormOptions::FIELD_EMAIL))
            $fieldset->add(
                array(
                    'type' => 'email',
                    'name' => RegistrationFormOptions::FIELD_EMAIL,
                    'options' => array(
                        'label' => /*@translate*/ 'Email',
                    ),
                    'attributes' => [
                        'required' => $formOptions->isRequired(RegistrationFormOptions::FIELD_EMAIL)
                    ],
                )
            );

        if (User::ROLE_RECRUITER === $role) {
            if ($formOptions->isEnabled(RegistrationFormOptions::FIELD_ORGANIZATION_NAME)){
                $fieldset->add(
                    array(
                        'type'       => 'text',
                        'name'       => RegistrationFormOptions::FIELD_ORGANIZATION_NAME,
                        'options'    => array(
                            'label' => /*@translate*/ 'Companyname',
                        ),
                        'attributes' => [
                            'required' => $formOptions->isRequired(RegistrationFormOptions::FIELD_ORGANIZATION_NAME)
                        ],
                    )
                );
            }

            if ($formOptions->isEnabled(RegistrationFormOptions::FIELD_POSTAL_CODE)){
                $fieldset->add(
                    [
                        'type'       => 'text',
                        'name'       => RegistrationFormOptions::FIELD_POSTAL_CODE,
                        'options'    => array(
                            'label' => /*@translate*/ 'Postalcode',
                        ),
                        'attributes' => [
                            'required' => $formOptions->isRequired(RegistrationFormOptions::FIELD_POSTAL_CODE)
                        ],
                    ]
                );
            }

            if ($formOptions->isEnabled(RegistrationFormOptions::FIELD_CITY)) {

                $fieldset->add(
                    array(
                        'type'       => 'text',
                        'name'       => 'city',
                        'options'    => [
                            'label' => /*@translate*/ 'City',
                        ],
                        'attributes' => [
                            'required' => $formOptions->isRequired(RegistrationFormOptions::FIELD_CITY)
                        ],
                    )
                );
            }

            if ($formOptions->isEnabled(RegistrationFormOptions::FIELD_STREET)) {
                $fieldset->add(
                    array(
                        'type'       => 'text',
                        'name'       => RegistrationFormOptions::FIELD_STREET,
                        'options'    => [
                            'label' => /*@translate*/ 'Street',
                        ],
                        'attributes' => [
                            'required' =>$formOptions->isRequired(RegistrationFormOptions::FIELD_STREET)
                        ],
                    )
                );
            }

            if ($formOptions->isEnabled(RegistrationFormOptions::FIELD_HOUSE_NUMBER)) {
                $fieldset->add(
                    array(
                        'type'       => 'text',
                        'name'       => RegistrationFormOptions::FIELD_HOUSE_NUMBER,
                        'options'    => [
                            'label' => /*@translate*/ 'house number',
                        ],
                        'attributes' => [
                            'required' => $formOptions->isRequired(RegistrationFormOptions::FIELD_HOUSE_NUMBER)
                        ],
                    )
                );
            }

            if ($formOptions->isEnabled(RegistrationFormOptions::FIELD_PHONE)) {
                $fieldset->add(
                    array(
                        'type'       => 'text',
                        'name'       => RegistrationFormOptions::FIELD_PHONE,
                        'options'    => [
                            'label' => /*@translate*/ 'Phone',
                        ],
                        'attributes' => [
                            'required' => $formOptions->isRequired(RegistrationFormOptions::FIELD_PHONE)
                        ],
                    )
                );
            }

            $fieldset->add(
                array(
                    'type'       => 'checkbox',
                    'name'       => 'differentInvoiceAddress',
                    'options'    => [
                        'label' => ' ',
                        'afterText' => /*@translate*/ 'Abweichende Rechnungsanschrift',
                    ]
                )
            );
        }

        $fieldset->add(
            array(
                'name'       => 'role',
                'type'       => 'hidden',
                'attributes' => array(
                    'value' => $role,
                ),
            )
        );

        $this->add($fieldset);

        $addressFieldset = new InvoiceAddressSettingsFieldset($formManager);
        $addressFieldset->init();
        $addressFieldset->setName('registerAddressFieldset');
        $addressFieldset->setLabel(/*@translate*/ 'Abweichende Rechnungsanschrift');
        $addressFieldset->setObject(new InvoiceAddress());
        $addressFieldset->setOptions(array('renderFieldset' => true));

        //TODO: workaround, otherwise required error is thrown
        $addressFieldset->get('gender')->setAttribute('required', false)->setValue('male');
        $this->add($addressFieldset);

        $mode = $options->getMode();
        if (in_array($mode, [CaptchaOptions::RE_CAPTCHA, CaptchaOptions::IMAGE])) {
            if ($mode == CaptchaOptions::IMAGE) {
                $captcha = new Image($options->getImage());
            } elseif ($mode == CaptchaOptions::RE_CAPTCHA) {
                $captcha = new ReCaptcha($options->getReCaptcha());
            }

            if (!empty($captcha)) {
                $this->add(
                    array(
                        'name'    => 'captcha',
                        'options' => array(
                            'label'   => /*@translate*/ 'Are you human?',
                            'captcha' => $captcha,
                        ),
                        'type'    => 'Zend\Form\Element\Captcha',
                    )
                );
            }
        }

        $buttons = new ButtonsFieldset('buttons');
        $buttons->add(
            array(
                'type' => 'submit',
                'name' => 'button',
                'attributes' => array(
                    'type' => 'submit',
                    'value' => /*@translate*/ 'Register',
                    'class' => 'btn btn-primary'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'csrf',
                'type' => 'csrf',
                'options' => array(
                    'csrf_options' => array(
                        'salt' => str_replace('\\', '_', __CLASS__),
                        'timeout' => 3600
                    )
                )
            )
        );

        $this->add($buttons);
    }
}