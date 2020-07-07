<?php

namespace Gastro24\Factory\Form;

use Core\Form\FileUploadFactory;
use Gastro24\Entity\OrganizationAdditional;
use Laminas\Stdlib\AbstractOptions;

/**
 * OrganizationBannerImageFactory.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class OrganizationBannerImageFactory extends FileUploadFactory
{
    protected $fileName = 'banner';
    protected $fileEntityClass = '\Gastro24\Entity\OrganizationAdditional';
    protected $configKey = 'organization_banner_image';

    /**
     * abstract options defined in "Applications/Options"
     *
     * @var string
     */
    protected $options="Jobs/Options";

    /**
     * Configure the Form width Options
     *
     * @param \Core\Form\Form $form
     * @param AbstractOptions $options
     */
    protected function configureForm($form, AbstractOptions $options)
    {
        $size = $options->getCompanyLogoMaxSize();
        $type = $options->getCompanyLogoMimeType();

        $form->setAllowedObjectBindingClass(OrganizationAdditional::class);

        $form->get($this->fileName)->setViewHelper('formImageUpload')
            ->setMaxSize(2000000)
            ->setAllowedTypes($type)
            ->setForm($form);
        $form->setIsDescriptionsEnabled(true);
        $form->setDescription(
        /*@translate*/ 'Choose a Logo. This logo will be shown in the job opening and the application form.'
        );
    }
}