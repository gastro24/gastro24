<?php

namespace Gastro24\Form\Organizations;

use Organizations\Form\Organizations as BaseOrganizationsForm;
use Organizations\Form\OrganizationsProfileForm;

/**
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class Organizations extends BaseOrganizationsForm
{
    public function init()
    {
        $this->setName('organization-form');

        $this->setForms(
            array(
                'nameForm' => array(
                    'type' => 'Organizations/OrganizationsNameForm',
                    'property' => true,
                ),

                'organizationLogo' => array(
                    'type' => 'Organizations/Image',
                    'property' => 'images',
                    'use_files_array' => true,

                    'options' => [
                        'label' => /*@translate*/ 'Logo',
                        'enable_descriptions' => false,
                    ]
                ),

                'organizationBanner' => array(
                    'type' => 'Organizations/Banner',
                    'property' => true,
                    'use_files_array' => true,

                    'options' => [
                        'label' => /*@translate*/ 'Bannerbild',
                        'enable_descriptions' => false,
                    ],
                ),

                'locationForm' => array(
                    'type' => 'Organizations/OrganizationsContactForm',
                    'property' => 'contact',
                ),

                'socialsForm' => array(
                    'type' => OrganizationsSocialForm::class,
                    'property' => true,
                ),

                'descriptionForm' => array(
                    'type' => OrganizationsDescriptionForm::class,
                    'property' => true,
                ),

                'employeesManagement' => array(
                    'type' => 'Organizations/Employees',
                    'property' => true,
                    'options' => array(
                        'label' => /*@translate*/ 'Employees',
                    ),
                ),

                'workflowSettings' => array(
                    'type' => 'Organizations/WorkflowSettings',
                    'property' => 'workflowSettings',
                    'options' => array(
                        'label' => /*@translate*/ 'Workflow',
                    ),
                ),

                'profileSettings' => [
                    'type' => OrganizationsProfileForm::class,
                    'property' => true,
                    'options' => [
                        'label' => /*@translate*/ 'Profile Setting',
                    ]
                ]
            )
        );
    }
}