<?php

namespace Gastro24\Form;

use Jobs\Entity\Location;
use \Jobs\Form\JobboardSearch as BaseJobboardSearchForm;

/**
 * JobboardSearch.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class JobboardSearch extends BaseJobboardSearchForm
{
    /** @var string */
    private $solrConnectionString;
    private $basePath;

    public function init()
    {
        $this->setAttribute('id', 'jobs-list-filter');
        $this->setOption('text_span', 5);
        $this->setOption('buttons_span', 1);
        $this->setName($this->getOption('name') ?: 'searchform');

        $name = $this->getOption('text_name') ?: 'q';
        $label = $this->getOption('text_label') ?: /*@translate*/ 'Search';
        $placeholder = $this->getOption('text_placeholder') ?: /*@translate*/ 'Job or Keyword';
        $span = $this->getOption('text_span') ?: 12;
        $priority = 50;
        $this->setButtonElement('d');

        $this->add(
            [
                'type' => 'Text',
                'options' => [
                    'label' => $label,
                    'span' => $span,
                ],
                'attributes' => [
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'data-url' => $this->getBasePath()
                ],
            ],
            [
                'name' => $name,
                'priority' => $priority,
            ]
        );
        $this->setOption('button_element', $name);

        $this->addElements();

        $this->add(
            [
                'name'       => 'l',
                'type'       => 'LocationSelect',
                'options'    => [
                    'label' => 'Location',
                    'span'  => 4,
                    'location_entity' => Location::class,
                ],
                'attributes' => [
                    'data-width' => '100%',
                ]
            ]
        );
        $this->add(
            array(
                'name'       => 'd',
                'type'       => 'Core\Form\Element\Select',
                'options'    => array(
                    'label'         => /*@translate*/ 'Distance',
                    'value_options' => [
                        '5'   => '5 km',
                        '10'  => '10 km',
                        '20'  => '20 km',
                        '50'  => '50 km',
                        '100' => '100 km'
                    ],
                    'span'          => 3,
                ),
                'attributes' => [
                    'value'            => '10', // default distance
                    'data-searchbox'   => -1,  // hide the search box
                    'data-allowclear'  => 'false', // allow to clear a selected value
                    'data-placeholder' => /*@translate*/ 'Distance',
                    'data-width'       => '100%',
                ]
            )
        );

        $this->addButton(/*@translate*/ 'Search', -1000, 'submit');
        $this->addButton(/*@translate*/ 'Clear', -1001, 'reset');
    }

    /**
     * @return string
     */
    public function getSolrConnectionString()
    {
        return $this->solrConnectionString;
    }

    /**
     * @param string $solrConnectionString
     */
    public function setSolrConnectionString($solrConnectionString)
    {
        $this->solrConnectionString = $solrConnectionString;
    }

    /**
     * @return mixed
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * @param mixed $basePath
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }


}