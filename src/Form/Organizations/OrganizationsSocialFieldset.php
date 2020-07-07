<?php

namespace Gastro24\Form\Organizations;

use Core\Entity\Hydrator\EntityHydrator;
use Laminas\Form\Fieldset;
use Laminas\InputFilter\InputFilterProviderInterface;

/**
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class OrganizationsSocialFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function getHydrator()
    {
        if (!$this->hydrator) {
            $hydrator = new EntityHydrator();
            $this->setHydrator($hydrator);
        }
        return $this->hydrator;
    }

    public function init()
    {
        $this->setName('socials');

        $this->add([
            'type' => 'Url',
            'name' => 'facebook',
            'options' => [
                'label' => /*@translate*/ 'Facebook Profil',
                'rowClass' => 'csj-uri-wrapper',
            ],
            'attributes' => [
                'data-placeholder' => 'https://',
                'placeholder' => 'https://',
            ]
        ]);

        $this->add([
            'type' => 'Url',
            'name' => 'linkedin',
            'options' => [
                'label' => /*@translate*/ 'LinkedIn Profil',
                'rowClass' => 'csj-uri-wrapper'
            ],
            'attributes' => [
                'data-placeholder' => 'https://',
                'placeholder' => 'https://',
            ]
        ]);

        $this->add([
            'type' => 'Url',
            'name' => 'youtube',
            'options' => [
                'label' => /*@translate*/ 'YouTube Profil',
                'rowClass' => 'csj-uri-wrapper'
            ],
            'attributes' => [
                'data-placeholder' => 'https://',
                'placeholder' => 'https://',
            ]
        ]);

        $this->add([
            'type' => 'Url',
            'name' => 'instagram',
            'options' => [
                'label' => /*@translate*/ 'Instagram Profil',
                'rowClass' => 'csj-uri-wrapper'
            ],
            'attributes' => [
                'data-placeholder' => 'https://',
                'placeholder' => 'https://',
            ]
        ]);

        $this->add([
            'type' => 'Url',
            'name' => 'twitter',
            'options' => [
                'label' => /*@translate*/ 'Twitter Profil',
                'rowClass' => 'csj-uri-wrapper'
            ],
            'attributes' => [
                'data-placeholder' => 'https://',
                'placeholder' => 'https://',
            ]
        ]);
    }

    public function getInputFilterSpecification()
    {
        return [
            'facebook' => [
                'required' => false,
                'allow_empty' => true
            ],
            'linkedin' => [
                'required' => false,
                'allow_empty' => true
            ],
            'youtube' => [
                'required' => false,
                'allow_empty' => true
            ],
            'instagram' => [
                'required' => false,
                'allow_empty' => true
            ],
            'twitter' => [
                'required' => false,
                'allow_empty' => true
            ],
        ];
    }
}