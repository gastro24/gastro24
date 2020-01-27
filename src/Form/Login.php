<?php

namespace Gastro24\Form;

use Core\Form\Form;
use Zend\Form\Fieldset;

/**
 * LoginForm.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class Login extends Form
{
    public function __construct($name = 'login-form', $options = array())
    {
        parent::__construct($name, $options);

        $this->setAttribute('data-handle-by', 'native');
        $this->setAttribute('class', 'form-inline');

        $fieldset = new Fieldset('credentials');
        $fieldset->setOptions(array('renderFieldset' => true));
        $fieldset->add(
            array(
                'name' => 'login',
                'options' => array(
                    'id' => 'login',
                ),
                'attributes' => [
                    'data-placeholder' => /*@translate*/ 'E-Mail Adresse',
                    'placeholder' => /*@translate*/ 'E-Mail Adresse',
                ],
            )
        );

        $fieldset->add(
            array(
                'type' => 'password',
                'name' => 'credential',
                'options' => array(
                    'id' => 'credential',

                ),
                'attributes' => [
                    'data-placeholder' => /*@translate*/ 'Passwort',
                    'placeholder' => /*@translate*/ 'Passwort',
                ],
            )
        );

        $fieldset->add(
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
        $this->add($fieldset);

        $buttons = new \Core\Form\ButtonsFieldset('buttons');
        $buttons->add(
            array(
                'type' => 'submit',
                'name' => 'button',
                'attributes' => array(
                    'id' => 'submit',
                    'type' => 'submit',
                    'value' => /*@translate*/ 'Login',
                    'class' => 'btn btn-primary btn-login'
                ),
            )
        );

        $this->add($buttons);
    }
}