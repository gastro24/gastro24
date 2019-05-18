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
            ],
            'Sprüngli'=> [
                'days' => 30
            ],
            'BÜRGENSTOCK HOTELS & RESORT'=> [
                'days' => 30
            ],
            'Coop'=> [
                'days' => 30
            ],
            'Kramer Gastronomie'=> [
                'days' => 30
            ],
            'gastrag.ch'=> [
                'days' => 30
            ],
            'Randstad (Schweiz) AG'=> [
                'days' => 30
            ],
            'Sv-group'=> [
                'days' => 30
            ],
            'Migros'=> [
                'days' => 30
            ]
        ]
    ],
    'paid' => [
        'days' => 365
    ],
    'single' => [
        'days' => 30
    ]
];

/* Do not edit below this line */

return [ 'options' => [ \Gastro24\Options\ConsoleDeleteJobs::class => [ $options ] ]];
