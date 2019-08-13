<?php

namespace Gastro24\View\Helper;

use DateTime;
use Jobs\Entity\Job;
use Organizations\Entity\Organization;
use Zend\Form\View\Helper\AbstractHelper;

/**
 * PublishDateFormatter.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class PublishDateFormatter extends AbstractHelper
{
    /**
     * @param Job $org
     * @return bool
     */
    public function __invoke($job)
    {
        // get date difference
        $today = new DateTime();
        $jobDate = $job->getDatePublishStart() ?? $job->getDateCreated();
        $dayDiff = $today->diff($jobDate);
        if ($dayDiff->days >= 1 && $dayDiff->days < 2) {
            $publishDate = /*@translate*/'Gestern';
        }
        elseif ($dayDiff->days >= 2 && $dayDiff->days <= 30) {
            $publishDate = /*@translate*/'vor ' . $dayDiff->days . ' Tagen';
        }
        elseif ($dayDiff->days > 30 ) {
            $publishDate = /*@translate*/'vor 30+ Tagen';
        }
        elseif ($dayDiff->days < 1 && $dayDiff->h < 1) {
            $publishDate = /*@translate*/'vor ' . $dayDiff->i . ' Minuten';
        }
        elseif ($dayDiff->days < 1) {
            if ($today->format('d') !== $jobDate->format('d')) {
                $publishDate = /*@translate*/'Gestern';
            }
            else {
                $publishDate = /*@translate*/'vor ' . $dayDiff->h . ' Stunden';
            }
        }
        else {
            $publishDate = $job->getDateCreated()->format('d.m.Y');
        }

        return $publishDate;
    }
}