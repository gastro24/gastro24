<?php

namespace Gastro24\Form\Organizations;

use Organizations\Form\Organizations as BaseOrganizationsForm;
use Organizations\Form\OrganizationsProfileForm;

/**
 * Organizations.php
 *
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
                    'options' => array(
                        'enable_descriptions' => true,
                        'description' => /*@translate*/ 'Please enter the name of the hiring organization.',
                    ),
                ),

                'organizationLogo' => array(
                    'type' => 'Organizations/Image',
                    'property' => 'images',
                    'use_files_array' => true,

                    'options' => [
                        'label' => /*@translate*/ 'Logo',
                    ]
                ),

                'organizationBanner' => array(
                    'type' => 'Organizations/Image',
                    'property' => 'images',
                    'use_files_array' => true,

                    'options' => [
                        'label' => /*@translate*/ 'Bannerbild',
                    ]
                ),

                'locationForm' => array(
                    'type' => 'Organizations/OrganizationsContactForm',
                    'property' => 'contact',
                    'options' => array(
                        'enable_descriptions' => true,
                        'description' => /*@translate*/ 'Please enter a contact for the hiring organization.',
                    ),
                ),

                'socialForm' => array(
                    'type' => 'Gastro24/Organizations/OrganizationsSocialForm',
                    'property' => 'socials',
                    'options' => array(
                        'enable_descriptions' => true,
                        'description' => /*@translate*/ 'Please enter a contact for the hiring organization.',
                    ),
                ),

                'descriptionForm' => array(
                    'type' => 'Organizations/OrganizationsDescriptionForm',
                    'property' => true,
                    'options' => array(
                        'enable_descriptions' => true,
                        'description' => /*@translate*/ 'Please enter a description for the hiring organization.',
                    ),
                ),

                'employeesManagement' => array(
                    'type' => 'Organizations/Employees',
                    'property' => true,
                    'options' => array(
                        'label' => /*@translate*/ 'Employees',
                        'enable_descriptions' => true,
                        'description' => /*@translate*/ 'Manage your employees and their permissions.',
                    ),
                ),

                'workflowSettings' => array(
                    'type' => 'Organizations/WorkflowSettings',
                    'property' => 'workflowSettings',
                    'options' => array(
                        'label' => /*@translate*/ 'Workflow',
                        'enable_descriptions' => true,
                        'description' => /*@translate*/ 'Define, how notifications about new applications reach your employees',
                    ),
                ),

                'profileSettings' => [
                    'type' => OrganizationsProfileForm::class,
                    'property' => true,
                    'options' => [
                        'label' => /*@translate*/ 'Profile Setting',
                        'enable_descriptions' => true,
                        'description' => /*@translate*/ 'Define how profile page should behave'
                    ]
                ]

            )
        );

//        $this->setForms(
//            array(
//                'nameForm' => array(
//                    'type' => 'Organizations/OrganizationsNameForm',
//                    'property' => true,
//                    'options' => [
//                        'enable_descriptions' => false,
//                    ]
//                ),
//
//                'locationForm' => array(
//                    'type' => 'Organizations/OrganizationsContactForm',
//                    'property' => 'contact',
//                    'options' => [
//                        'enable_descriptions' => false,
//                    ]
//                ),
//
//                'organizationLogo' => array(
//                    'type' => 'Organizations/Image',
//                    'property' => 'images',
//                    'use_files_array' => true,
//
//                    'options' => [
//                        'label' => /*@translate*/ 'Logo',
//                        'enable_descriptions' => false,
//                    ]
//                ),
//
////                'organizationBanner' => array(
////                    'type' => 'Organizations/Image',
////                    'property' => 'images',
////                    'use_files_array' => true,
////
////                    'options' => [
////                        'label' => /*@translate*/ 'Bannerbild',
////                        'enable_descriptions' => false,
////                    ]
////                ),
//
//                'descriptionForm' => array(
//                    'type' => 'Organizations/OrganizationsDescriptionForm',
//                    'property' => true,
//                    'options' => [
//                        'enable_descriptions' => false,
//                    ]
//                ),
//
//                'employeesManagement' => array(
//                    'type' => 'Organizations/Employees',
//                    'property' => true,
//                    'options' => array(
//                        'label' => /*@translate*/ 'Employees',
//                        'enable_descriptions' => false,
//                    ),
//                ),
//
//                'workflowSettings' => array(
//                    'type' => 'Organizations/WorkflowSettings',
//                    'property' => 'workflowSettings',
//                    'options' => array(
//                        'label' => /*@translate*/ 'Workflow',
//                        'enable_descriptions' => false,
//                    ),
//                ),
//
//                'profileSettings' => [
//                    'type' => OrganizationsProfileForm::class,
//                    'property' => true,
//                    'options' => [
//                        'label' => /*@translate*/ 'Profile Setting',
//                        'enable_descriptions' => false,
//                    ]
//                ]
//
//            )
//        );
    }
}