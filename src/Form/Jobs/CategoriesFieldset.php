<?php

namespace Gastro24\Form\Jobs;

use Core\Entity\Hydrator\EntityHydrator;
use Laminas\Form\Fieldset;

class CategoriesFieldset extends Fieldset
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
        $catValues = $this->landingPageOptions->getCategoryValues();
        ksort($catValues);
        $options = array_merge(['' => ''], $catValues);
        $this->add([
            'type' => 'Core/Select',
            'name' => 'category',
            'options' => [
                'label' => 'Kategorie',
                'value_options' => $options,
            ],
            'attributes' => [
                'data-width' => '100%',
                'multiple' => false,
            ],
        ]);
    }
}