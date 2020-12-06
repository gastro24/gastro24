<?php
/**
 * YAWIK
 *
 * @filesource
 * @copyright (c) 2013-2015 Cross Solution (http://cross-solution.de)
 * @license   MIT
 */
/*
 * here you can add your channels
 */

$channel['social-media'] = array(

    'label' => 'Job-Boost 1',
    'prices' => [ 'base' => 150, 'list' => 150, 'min'  => 150, ],
    'headline' => /*@translate*/ 'Social Media Werbung',
    'description' => /*@translate*/ 'Publikation auf Facebook & Instagram (inkl. CHF 50.- Ads-Budget) während 5 Tagen.',
    'route' => 'lang/content',
    'publishDuration' => 5,
    'params' => array(
        'view' => 'option1'
    )
);

$channel['top-listing-home'] = array(

    'label' => 'Job-Boost 2',
    'prices' => [ 'base' => 95, 'list' => 95, 'min'  => 95, ],
    'headline' => /*@translate*/ 'Top-Listing Homepage',
    'description' => /*@translate*/ 'Premium-Platzierung oben auf der Startseite während 30 Tagen.',
    'route' => 'lang/content',
    'publishDuration' => 30,
    'params' => array(
        'view' => 'option2'
    )
);

$channel['top-listing-search'] = array(

    'label' => 'Job-Boost 3',
    'prices' => [ 'base' => 55, 'list' => 55, 'min'  => 55, ],
    'headline' => /*@translate*/ ' Top-Listing Suche',
    'description' => /*@translate*/ 'Kommt Ihre Stellenanzeige in einer Suche vor, wird sie automatisch während 30 Tagen oberhalb der klassischen Stelleninserate angezeigt und hervorgehoben.',
    'route' => 'lang/content',
    'publishDuration' => 30,
    'params' => array(
        'view' => 'option3'
    )
);
return array('multiposting'=> array('channels' => $channel));