<?php

namespace Gastro24\Form\Filter;

use Auth\Entity\User;
use Zend\InputFilter\Factory;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Identical;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;

/**
 * SimpleRegisterInputFilter.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class SimpleRegisterInputFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(
            array(
                'name' => 'email',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'EmailAddress',
                        'options' => array(),
                    ),
                ),
            )
        );

        $this->add(
            array(
                'name' => 'firstname',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 3,
                            'max' => 255,
                        ),
                    ),
                ),
            )
        );

        $this->add(
            array(
                'name' => 'lastname',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 3,
                            'max' => 255,
                        ),
                    ),
                ),
            )
        );

        $this->add(
            array(
                'name' => 'password',
                'required' => true,
                'filters' => array(
                    array('name' => '\Zend\Filter\StringTrim'),
                    array('name' => '\Zend\Filter\StripTags'),
                ),
                'validators' => array(
                    new NotEmpty(),
                    new StringLength(array('min' => 6, 'max' => 50))
                ),
            )
        );

        $this->add(
            array(
                'name' => 'password2',
                'required' => true,
                'filters' => array(
                    array('name' => '\Zend\Filter\StringTrim'),
                ),
                'validators' => array(
                    new NotEmpty(),
                    new StringLength(array('min' => 6, 'max' => 50)),
                    new Identical('password'),
                ),
            )
        );
    }
}