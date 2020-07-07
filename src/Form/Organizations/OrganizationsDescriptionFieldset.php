<?php

namespace Gastro24\Form\Organizations;

use Organizations\Form\OrganizationsDescriptionFieldset as BaseOrganizationsDescriptionFieldset;
use Laminas\InputFilter\InputFilterProviderInterface;

/**
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class OrganizationsDescriptionFieldset extends BaseOrganizationsDescriptionFieldset implements InputFilterProviderInterface
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
            ]
        ]);

        parent::init();
    }

    public function getInputFilterSpecification()
    {
        return [
            'videoLink' => [
                'required' => false,
                'allow_empty' => true
            ]
        ];
    }
}