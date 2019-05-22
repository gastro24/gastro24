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
            '5a0809397bb2b582267c7a97' => [
                'name' => 'Adecco',
                'days' => 30
            ],
            '5bcf612fb6428b0b1008db60' => [
                'name' => 'McDonald\'s Suisse Management & Services Sàrl',
                'days' => 30
            ],
            '5bc6e561b6428b641322dbf9' => [
                'name' => 'Gilde Restaurants',
                'days' => 30
            ],
            '5aa7e1d77bb2b57b5d3c03e4'=> [
                'name' => 'zfv Die Gastronomiegruppe',
                'days' => 30
            ],
            '58a4a59d4e197f17047b23c6'=> [
                'name' => 'Sprüngli',
                'days' => 30
            ],
            '5bcdfda2b6428ba17c5c9048'=> [
                'name' => 'BÜRGENSTOCK HOTELS & RESORT',
                'days' => 30
            ],
            '59d7d04e7bb2b594121c1235'=> [
                'name' => 'Coop',
                'days' => 30
            ],
            '5aaf724f7bb2b5233a03ed25'=> [
                'name' => 'Kramer Gastronomie',
                'days' => 30
            ],
            '5bd21420b6428bfb4523562e'=> [
                'name' => 'gastrag.ch',
                'days' => 30
            ],
            '5a970fea7bb2b5a578812d52'=> [
                'name' => 'Randstad (Schweiz) AG',
                'days' => 30
            ],
            '59e4b53e7bb2b553412f9be9'=> [
                'name' => 'Sv-group',
                'days' => 30
            ],
            '5a054fa37bb2b593231413a0'=> [
                'name' => 'Migros',
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
