<?php

namespace Gastro24\Form\Filter;

use Zend\InputFilter\Factory;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Digits;
use Zend\Validator\Identical;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;

/**
 * CompanyRegisterInputFilter.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class CompanyRegisterInputFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(
            array(
                'name' => 'organizationName',
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
                'name' => 'phone',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => [
                    [
                        'name' => 'Callback',
                        'options' => [
                            'callback' => [$this, 'validatePhone'],
                            'callbackOptions' => [
                                'format' => 'intl'
                            ]
                        ]
                    ]
                ]
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
                'name' => 'postalCode',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    new Digits(),
                ),
            )
        );
    }

    // Custom validator for a phone number.
    public function validatePhone($value, $context, $format)
    {
        // Determine the correct length and pattern of the phone number,
        // depending on the format.
//        if($format == 'intl') {
//            $correctLength = 16;
//            $pattern = '/^\d\ (\d{3}\) \d{3}-\d{4}$/';
//        } else { // 'local'
//            $correctLength = 8;
//            $pattern = '/^\d{3}-\d{4}$/';
//        }
//
//        // Check phone number length.
//        if(strlen($value)!=$correctLength)
//            return false;

        $pattern = '/^[0-9-+\s()]*$/';
        // Check if the value matches the pattern.
        $matchCount = preg_match($pattern, $value);

        return ($matchCount!=0)?true:false;
    }
}