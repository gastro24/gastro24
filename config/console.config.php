<?php
/**
 * YAWIK
 *
 * @filesource
 * @copyright (c) 2013 - 2016 Cross Solution (http://cross-solution.de)
 * @license   MIT
 */

return [
    'console' => [
        'router' => [
            'routes' => [
                'jobs-expire'    => [
                    'options' => [
                        'route'    => 'jobs expire [--days=] [--limit=] [--info]',
                        'defaults' => [
                            'controller' => 'Gastro24/Jobs/Console',
                            'action'     => 'expirejobs',
                            'days'       => 30,
                            'limit'      => '10,0',
                        ],
                    ],
                ]
            ]
        ]
    ]
];
