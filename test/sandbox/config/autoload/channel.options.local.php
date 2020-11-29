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

$channel['yawik'] = array(
             
                'label' => 'yawik',
                'prices' => [ 'base' => 150, 'list' => 150, 'min'  => 150, ],
                'headline' => /*@translate*/ 'test',
                'description' => /*@translate*/ 'test',
                'route' => 'lang/content',
                'publishDuration' => 15,
                'params' => array(
                    'view' => 'jobs-publish-on-yawik'
                )
            );

return array('multiposting'=> array('channels' => $channel));