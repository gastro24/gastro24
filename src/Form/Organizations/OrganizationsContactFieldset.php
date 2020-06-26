<?php

namespace Gastro24\Form\Organizations;

use Organizations\Form\OrganizationsContactFieldset as BaseOrganizationsContactFieldset;

/**
 * OrganizationsContactFieldset.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class OrganizationsContactFieldset extends BaseOrganizationsContactFieldset
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
                //'class' => 'form-control',
            ]
        ]);

        $this->remove('fax');
    }
}