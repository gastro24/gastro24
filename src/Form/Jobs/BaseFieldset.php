<?php
namespace Gastro24\Form\Jobs;

use Core\Entity\Hydrator\MappingEntityHydrator;
use Doctrine\Common\Collections\ArrayCollection;
use Jobs\Entity\Location;
use Jobs\Form\BaseFieldset as CoreBaseFieldset;

class BaseFieldset extends CoreBaseFieldset
{
    /**
     * @return \Laminas\Hydrator\HydratorInterface
     */
    public function getHydrator()
    {
        if (!$this->hydrator) {
            $hydrator = new MappingEntityHydrator([
                'locations' => 'geoLocation'
            ]);

            $geoLocationIsMultiple = $this->get('geoLocation')->getAttribute('multiple', false);
            $geoLocationStrategy = $this->get('geoLocation')->getHydratorStrategy();

            $locationsStrategy = new \Laminas\Hydrator\Strategy\ClosureStrategy(
                /* extract */
                function ($value) use ($geoLocationStrategy, $geoLocationIsMultiple) {
                    $streetname = $this->get('streetname')->getValue() ?? '';
                    $zipCode = $this->get('zip')->getValue() ?? '';
                    $value = $geoLocationIsMultiple ? $value : $value->first();
                    /** @var Location $loc */
                    foreach ($value->getValues() as $loc) {
                        $loc->setStreetname($streetname);
                        if ($zipCode) {
                            $loc->setPostalCode($zipCode);
                        }
                    }

                    return $geoLocationStrategy->extract($value);
                },

                /* hydrate */
                function ($value) use ($geoLocationStrategy, $geoLocationIsMultiple) {
                    if ($geoLocationIsMultiple) {
                        return $geoLocationStrategy->hydrate($value);
                    }

                    return new ArrayCollection([$geoLocationStrategy->hydrate($value)]);
                }
            );

            $hydrator->addStrategy('locations', $locationsStrategy);

            $this->setHydrator($hydrator);
            $this->get('streetname')->setValue($this->getObject()->getLocations()->first()->getStreetname());
            $this->get('zip')->setValue($this->getObject()->getLocations()->first()->getPostalCode());
        }
        return $this->hydrator;
    }

    public function init()
    {
        $this->setAttribute('id', 'job-fieldset');

        $this->setName('jobBase');

        $this->add(
            [
                'type' => 'Text',
                'name' => 'title',
                'options' => [
                    'label' => /*@translate*/ 'Job title',
                    'description' => /*@translate*/ 'Please enter the job title'
                ],
            ]
        );

        $this->add([
            'type' => 'text',
            'name' => 'streetname',
            'options' => [
                'label' => /*@translate*/ 'street',
            ],
            'attributes' => [
                'data-width' => '100%',
            ]
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'zip',
            'options' => [
                'label' => /*@translate*/ 'Postalcode',
            ],
            'attributes' => [
                'data-width' => '80%',
            ]
        ]);

        $this->add(
            [
                'type' => 'LocationSelect',
                'name' => 'geoLocation',
                'options' => [
                    'label' => /*@translate*/ 'Location',
                    'description' => /*@translate*/ 'Please enter the location of the job',
                    'location_entity' => Location::class,
                    'summary_value' => [$this, 'getLocationsSummaryValue'],
                ],
                'attributes' => [
                    'data-width' => '100%',
                ]
            ]
        );
    }
}