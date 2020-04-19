<?php

namespace Gastro24\Form;

use Auth\Entity\User;
use Auth\Form\RegisterFormInterface;
use CompanyRegistration\Options\RegistrationFormOptions;
use Core\Form\Form;
use Zend\Form\FormElementManager\FormElementManagerV3Polyfill as FormElementManager;

/**
 * SimpleRegisterForm.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class SimpleRegisterForm extends Form implements RegisterFormInterface
{
    /**
     * @var FormElementManager
     */
    protected $formManager;

    public function __construct(FormElementManager $formManager, $name = 'simple-register-form')
    {
        parent::__construct($name, []);
        $this->formManager = $formManager;

        $this->setAttribute('data-handle-by', 'native');
        $this->setAttribute('class', 'simple-registration-form');

        $this->add([
                'type' => 'Zend\Form\Element\Email',
                'name' => RegistrationFormOptions::FIELD_EMAIL,
                'options' => array(
                    'label' => /*@translate*/ 'E-Mail Adresse',
                    'labelWidth' => '12'
                ),
                'attributes' => [
                    'required' => true,
                    'class' => 'form-control'
                ],
            ]
        );

        $this->add([
                'type' => 'Zend\Form\Element\Password',
                'name' => 'password',
                'options' => array(
                    'label' => /* @translate */ 'Password',
                    'labelWidth' => '12'
                ),
                'attributes' => [
                    'required' => true,
                    'class' => 'form-control'
                ],
            ]
        );

        $this->add([
                'type' => 'Zend\Form\Element\Password',
                'name' => 'password2',
                'options' => array(
                    'label' => /* @translate */ 'Retype password',
                    'labelWidth' => '12'
                ),
                'attributes' => [
                    'required' => true,
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

        $this->add([
            'type' => 'submit',
            'name' => 'button',
            'attributes' => array(
                'type' => 'submit',
                'value' => /*@translate*/ 'Schritt 1: Als Arbeitgeber registrieren',
                'class' => 'btn btn-primary registration-button'
            ),
        ]);
    }

    public function allowObjectBinding($object)
    {
        return $object instanceof User;
    }
}