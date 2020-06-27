<?php

namespace Gastro24\Form\Organizations;

use Core\Entity\Hydrator\EntityHydrator;
use Zend\Form\Fieldset;

/**
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class OrganizationsSocialFieldset extends Fieldset
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
                'rowClass' => 'csj-uri-wrapper'
            ],
            'attributes' => [
                'data-placeholder' => 'https://',
                'placeholder' => 'https://',
                //'class' => 'form-control',
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
                //'class' => 'form-control',
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
                //'class' => 'form-control',
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
                //'class' => 'form-control',
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
                //'class' => 'form-control',
            ]
        ]);
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array();
    }

    /**
     * a required method to overwrite the generic method to make the binding of the entity work
     * @param object $object
     * @return bool
     */
//    public function allowObjectBinding($object)
//    {
//        return $object instanceof OrganizationSocials;
//    }
}