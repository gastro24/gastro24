<?php

/*
 * Configuration for command delete expired jobs.
 *
 */

$options = [

    'crawler' => [
        'days' => 30

    ],
    'paid' => [
        'days' => 365
    ]
];

/* Do not edit below this line */

return [ 'options' => [ \Gastro24\Options\ConsoleDeleteJobs::class => [ $options ] ]];
