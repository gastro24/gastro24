<?php

namespace Gastro24\Form;

use Core\Form\ButtonsFieldset;
use Core\Form\Form;

/**
 * ForgotPassword.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class ForgotPasswordPopup extends Form
{
    public function __construct($name = 'forgot-password', $options = array())
    {
        parent::__construct($name, $options);

        $this->setAttribute('data-handle-by', 'native');
        $this->setAttribute('class', 'form-horizontal');

        $this->add(
            array(
                'type' => 'text',
                'name' => 'identity',
                'options' => array(
                    'is_disable_capable' => false,
                ),
                'attributes' => [
                    'data-placeholder' => /* @translate */ 'Username or email',
                    'placeholder' => /* @translate */ 'Username or email',
                ],
            )
        );

        $buttons = new ButtonsFieldset('buttons');
        $buttons->setAttribute('class', 'row');
        $buttons->add(
            array(
                'type' => 'submit',
                'name' => 'button',
                'attributes' => array(
                    'id' => 'submit',
                    'type' => 'submit',
                    'value' => /* @translate */ 'Reset your password',
                    'class' => 'btn btn-primary btn-reset'
                ),
            )
        );

        $this->add(
            array(
                'name' => 'csrf',
                'type' => 'csrf',
            )
        );

        $this->add($buttons);
    }
}