<?php

namespace Gastro24\View\Helper\Jobs;

use Jobs\Entity\Job;

/**
 * Class JobLocationHelper
 * @package Gastro24\View\Helper\Jobs
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class JobLocationHelper
{
    /**
     * @param Job $job
     * @return string
     */
    public static function getLocationStringForBreadcrumb(Job $job)
    {
        $location = $job->getLocations()->current();

        if ($location == '') {
            return 'in der Schweiz';
        }
        elseif ($location && $location->getCity()) {
            return 'in ' . $location->getCity();
        }

        return 'in der Schweiz';
    }

}