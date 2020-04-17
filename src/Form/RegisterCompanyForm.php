<?php

namespace Gastro24\Form;

use Auth\Entity\User;
use Auth\Form\RegisterFormInterface;
use Auth\Form\UserPasswordFieldset;
use Auth\Options\CaptchaOptions;
use CompanyRegistration\Form\Register;
use CompanyRegistration\Options\RegistrationFormOptions;
use Core\Form\ButtonsFieldset;
use Core\Form\Form;
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
 * RegisterCompanyForm.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class RegisterCompanyForm extends Form implements RegisterFormInterface
{
    /**
     * @var FormElementManager
     */
    protected $formManager;

    public function __construct(FormElementManager $formManager, $name = 'registration-company-form')
    {
        parent::__construct($name, []);
        $this->formManager = $formManager;

        $this->setAttribute('data-handle-by', 'native');
        $this->setAttribute('class', 'registration-company-form');

        $this->add(
            array(
                'type'       => 'text',
                'name'       => RegistrationFormOptions::FIELD_ORGANIZATION_NAME,
                'options'    => array(
                    'label' => /*@translate*/ 'Companyname',
                    'labelWidth' => '6'
                ),
                'attributes' => [
                    'required' => true,
                    'class' => 'form-control'
                ],
            )
        );

        $this->add([
                'type'       => 'text',
                'name'       => 'representative',
                'label' => /*@translate*/ 'Vertreter',
                'options'    => array(
                    'label' => /*@translate*/ 'Vertreter',
                    'labelWidth' => '6'
                ),
                'attributes' => [
                    'class' => 'form-control'
                ],
            ]
        );

        $this->add(
            array(
                'type' => 'Zend\Form\Element\Email',
                'name' => 'email',
                'options' => array(
                    'label' => /*@translate*/ 'E-Mail Adresse',
                    'labelWidth' => '12'
                ),
                'attributes' => [
                    'required' => true,
                    'class' => 'form-control'
                ],
            )
        );

        $this->add(
            array(
                'type'       => 'text',
                'name'       => RegistrationFormOptions::FIELD_PHONE,
                'options'    => [
                    'label' => /*@translate*/ 'Phone',
                ],
                'attributes' => [
                    'placeholder' => '+41 78 123 4567',
                    'required' => false,
                    'class' => 'form-control'
                ],
            )
        );

        $this->add([
                'type'       => 'text',
                'name'       => 'vat',
                'label' => /*@translate*/ 'USt-ID-Nr/UID',
                'options'    => array(
                    'label' => /*@translate*/ 'USt-ID-Nr/UID',
                    'labelWidth' => '6'
                ),
                'attributes' => [
                    'class' => 'form-control'
                ],
            ]
        );

        $this->add(
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
                    'required'         => true,
                    'class' => 'form-control'
                ],
            ]
        );

        $this->add([
                'type'       => 'text',
                'name'       => 'firstname',
                'options'    => array(
                    'label' => /*@translate*/ 'Vorname',
                    'labelWidth' => '6'
                ),
                'attributes' => [
                    'required' => true,
                    'class' => 'form-control'
                ],
            ]
        );

        $this->add([
                'type'       => 'text',
                'name'       => 'lastname',
                'label' => /*@translate*/ 'Nachname',
                'options'    => array(
                    'label' => /*@translate*/ 'Nachname',
                    'labelWidth' => '6'
                ),
                'attributes' => [
                    'required' => true,
                    'class' => 'form-control'
                ],
            ]
        );

        $this->add(
            array(
                'type'       => 'text',
                'name'       => RegistrationFormOptions::FIELD_STREET,
                'options'    => [
                    'label' => /*@translate*/ 'Street',
                ],
                'attributes' => [
                    'required' => true,
                    'class' => 'form-control'
                ],
            )
        );

        $this->add(
            array(
                'type'       => 'text',
                'name'       => 'addressSuffix',
                'options'    => [
                    'label' => /*@translate*/ 'Address suffix',
                ],
                'attributes' => [
                    'class' => 'form-control'
                ],
            )
        );

        $this->add(
            [
                'type'       => 'text',
                'name'       => RegistrationFormOptions::FIELD_POSTAL_CODE,
                'options'    => array(
                    'label' => /*@translate*/ 'Postalcode',
                ),
                'attributes' => [
                    'required' => true,
                    'class' => 'form-control'
                ],
            ]
        );
        $this->add(
            array(
                'type'       => 'text',
                'name'       => 'city',
                'options'    => [
                    'label' => /*@translate*/ 'City',
                ],
                'attributes' => [
                    'required' => true,
                    'class' => 'form-control'
                ],
            )
        );

        $this->add(
            [
                'name'       => 'country',
                'type'       => 'Zend\Form\Element\Select',
                'options'    => [
                    'label'         => /*@translate */ 'Country',
                    'value_options' => [
                        'swiss'   => /*@translate */ 'Schweiz',
                    ]
                ],
                'attributes' => [
                    'data-placeholder' => /*@translate*/ 'please select',
                    'data-allowclear'  => 'false',
                    'data-searchbox'   => -1,
                    'required'         => false,
                    'class' => 'form-control'
                ],
            ]
        );

        $this->add([
            'type' => 'hidden',
            'name' => 'pt',
            'attributes' => [
                'value' => 'plus'
            ],
        ]);

        $this->add([
            'type' => 'submit',
            'name' => 'button',
            'attributes' => array(
                'type' => 'submit',
                'value' => /*@translate*/ 'Speichern',
                'class' => 'btn btn-primary registration-button'
            ),
        ]);
    }
}