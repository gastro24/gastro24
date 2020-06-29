<?php

namespace Gastro24\Form\Organizations;

use Organizations\Form\OrganizationsContactFieldset as BaseOrganizationsContactFieldset;
use Zend\InputFilter\InputFilterProviderInterface;

/**
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class OrganizationsContactFieldset extends BaseOrganizationsContactFieldset implements InputFilterProviderInterface
{

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        $this->add([
            'type' => 'Url',
            'name' => 'website',
            'options' => [
                'label' => /*@translate*/ 'Unternehmenswebsite',
                'rowClass' => 'csj-uri-wrapper'
            ],
            'attributes' => [
                'data-placeholder' => 'https://',
                'placeholder' => 'https://',
            ]
        ]);

        $this->remove('fax');
    }

    public function getInputFilterSpecification()
    {
        return [
            'website' => [
                'required' => false,
                'allow_empty' => true
            ]
        ];
    }
}