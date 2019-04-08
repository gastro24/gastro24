<?php

namespace Gastro24\Form;

use Core\Form\CustomizableFieldsetTrait;
use Jobs\Entity\Location;
use \Jobs\Form\JobboardSearch as BaseJobboardSearchForm;
/**
 * JobboardSearch.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class JobboardSearch extends BaseJobboardSearchForm
{
    use CustomizableFieldsetTrait;

    public function init()
    {
        $this->setAttribute('id', 'jobs-list-filter');
        $this->setOption('text_span', 5);

        $this->setName($this->getOption('name') ?: 'searchform');

        $this->addTextElement(
            $this->getOption('text_name') ?: 'q',
            $this->getOption('text_label') ?: /*@translate*/ 'Search',
            $this->getOption('text_placeholder') ?: /*@translate*/ 'Beruf, Begriff oder Arbeitsort',
            $this->getOption('text_span') ?: 12,
            50,
            true
        );

        $this->addButton(/*@translate*/ 'Search', -1000, 'submit');
        $this->addButton(/*@translate*/ 'Clear', -1001, 'reset');

        $this->addElements();

        $this->setButtonElement('q');

        $this->add(
            [
                'name'       => 'l',
                'type'       => 'LocationSelect',
                'options'    => [
                    'label' => 'Location',
                    'span'  => 3,
                    'location_entity' => new Location(),
                ],
                'attributes' => [
                    'data-width' => '100%',
                ]
            ]
        );
        $this->setButtonElement('l');

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
                    'span'          => 4,
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
        $this->setButtonElement('d');
    }
}