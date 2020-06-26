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
    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->setName('organization-description');

        $this->add([
            'type' => 'Url',
            'name' => 'videolink',
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

        $this->add(
            array(
                'name' => 'description',
                'type' => 'textarea',
                'options' => array(
                    'label' => /* @translate */ 'Description'
                )
            )
        );

    }
}