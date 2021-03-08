<?php

namespace Gastro24\Form\Jobs;

use Core\Entity\Hydrator\EntityHydrator;
use Laminas\Form\Fieldset;
use Laminas\InputFilter\InputFilterProviderInterface;

class CategoriesFieldset extends Fieldset implements InputFilterProviderInterface
{
    /**
     * @var \Gastro24\Options\Landingpages
     */
    private $landingPageOptions;

    public function __construct($landingPageOptions, $name = 'categories')
    {
        parent::__construct($name, []);
        $this->landingPageOptions = $landingPageOptions;
    }

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
        $this->setName('category');
        //$parentCategories = $this->landingPageOptions->getParentCategories();
        $parentCategories = $this->landingPageOptions->getCategoryValues();
        ksort($parentCategories);
        $options = array_merge(['' => ''], $parentCategories);
        $this->add([
            'type' => 'Core/Select',
            'name' => 'category',
            'options' => [
                'label' => 'Kategorie',
                'value_options' => $options,
            ],
            'attributes' => [
                'id' => 'category',
                'data-width' => '100%',
                'multiple' => false,
            ],
        ]);

        $allCategories = $this->landingPageOptions->getCategoryValues();
        ksort($allCategories);
        $options = array_merge(['' => ''], $allCategories);
        $this->add([
            'type' => 'Core/Select',
            'name' => 'subcategory',
            'options' => [
                'label' => 'Unterkategorie',
                'value_options' => $options,
                'disable_inarray_validator' => true
            ],
            'attributes' => [
                'id' => 'subcategory',
                'data-width' => '100%',
                'multiple' => false,
            ],
        ]);
    }

    public function getInputFilterSpecification()
    {
        $spec = [
            'category' => [
                'required' => false,
                'allow_empty' => true
            ],
            'subcategory' => [
                'required' => false,
                'allow_empty' => true
            ],
        ];

        return $spec;
    }
}