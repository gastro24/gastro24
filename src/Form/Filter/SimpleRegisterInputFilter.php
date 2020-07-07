<?php

namespace Gastro24\Form\Filter;

use Auth\Entity\User;
use Laminas\InputFilter\Factory;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\Identical;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\StringLength;

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
                    array('name' => '\Laminas\Filter\StringTrim'),
                    array('name' => '\Laminas\Filter\StripTags'),
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
                    array('name' => '\Laminas\Filter\StringTrim'),
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