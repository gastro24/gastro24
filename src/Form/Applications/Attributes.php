<?php

namespace Gastro24\Form\Applications;

use Applications\Form\Attributes as BaseForm;

/**
 * Attributes.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class Attributes extends BaseForm
{

    /**
     * initialize attributes form
     */
    public function init()
    {
        $this->setIsDisableCapable(false)
            ->setIsDisableElementsCapable(false)
            ->setAttribute('data-submit-on', 'checkbox');

        $this->add(
            array(
                'type' => 'infoCheckBox',
                'name' => 'sendCarbonCopy',
                'options' => array(
                    'headline' => /*@translate*/ 'Carbon Copy',
                    'long_label' => /*@translate*/ 'send me a carbon copy of my application',
                ),
                'attributes' => array(
                    'data-validate' => 'sendCarbonCopy',
                    'data-trigger'  => 'submit',
                ),
            )
        );

        $this->add(
            array(
                'type' => 'infoCheckBox',
                'name' => 'acceptedPrivacyPolicy',
                'options' => array(
                    'headline' => /*@translate*/ 'Privacy Policy',
                    'long_label' => /*@translate*/ 'I have read the %s and accept it',
                    'linktext' => /*@translate*/ 'Privacy Policy',
                    'route' => 'lang/content',
                    'params' => array(
                        'view' => 'applications-privacy-policy'
                    )
                ),
                'attributes' => array(
                    'data-validate' => 'acceptedPrivacyPolicy',
                    'data-trigger' => 'submit',
                ),
            )
        );
    }
}