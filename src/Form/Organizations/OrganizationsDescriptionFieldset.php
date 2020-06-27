<?php

namespace Gastro24\Form\Organizations;

use Organizations\Form\OrganizationsDescriptionFieldset as BaseOrganizationsDescriptionFieldset;

/**
 * OrganizationsDescriptionFieldset.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class OrganizationsDescriptionFieldset extends BaseOrganizationsDescriptionFieldset
{
    public function init()
    {
        $this->add([
            'type' => 'Url',
            'name' => 'videoLink',
            'options' => [
                'label' => /*@translate*/ 'Video Link',
                'rowClass' => 'csj-uri-wrapper'
            ],
            'attributes' => [
                'data-placeholder' => 'https://www.youtube.com/embed/123xyz',
                'placeholder' => 'https://www.youtube.com/embed/123xyz',
                //'class' => 'form-control',
            ]
        ]);

        parent::init();
    }
}