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
        $categories = $this->landingPageOptions->getCategoryValues();
        ksort($categories);
        $this->add([
            'type' => 'Core/Select',
            'name' => 'category',
            'options' => [
                'label' => 'Kategorie',
                'value_options' => $categories,
                'empty_option' => '-- keine Auswahl --'
            ],
            'attributes' => [
                'id' => 'category',
                'data-width' => '100%',
                'data-searchbox'   => 1,
                'multiple' => false,
            ],
        ]);

        $categories = $this->landingPageOptions->getCategoryValues();
        ksort($categories);
        $this->add([
            'type' => 'Core/Select',
            'name' => 'subcategory',
            'options' => [
                'label' => 'Unterkategorie',
                'value_options' => $categories,
                'disable_inarray_validator' => true,
                'empty_option' => '-- keine Auswahl --'
            ],
            'attributes' => [
                'id' => 'subcategory',
                'data-width' => '100%',
                'data-searchbox'   => 1,
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