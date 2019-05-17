<?php

/*
 * Configuration for command delete expired jobs.
 *
 * Define how long a job should be inactive before it will be deleted from database.
 *
 */

$options = [

    'crawler' => [
        'organizations' => [
            'Adecco' => [
                'days' => 30
            ],
            'McDonald\'s Suisse Management & Services Sàrl' => [
                'days' => 30
            ],
            'Gilde Restaurants' => [
                'days' => 30
            ],
            'zfv Die Gastronomiegruppe'=> [
                'days' => 30
            ]
        ]
    ],
    'paid' => [
        'days' => 365
    ]
];

/* Do not edit below this line */

return [ 'options' => [ \Gastro24\Options\ConsoleDeleteJobs::class => [ $options ] ]];
