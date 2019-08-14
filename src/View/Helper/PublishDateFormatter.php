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
        // workaround for timezone hours difference
        $time = strtotime($jobDate->format('y-m-d\TH:i:s.u'). '+04:00');
        $jobDate->setTimestamp($time);

        $dayDiff = $today->diff($jobDate);
        if ($dayDiff->days >= 1 && $dayDiff->days < 2) {
            if ($dayDiff->h > 0) {
                $publishDate = /*@translate*/'vor ' . ($dayDiff->days + 1) . ' Tagen';
            }
            else {
                $publishDate = /*@translate*/'Gestern';
            }
        }
        elseif ($dayDiff->days >= 2 && $dayDiff->days <= 29) {
            $todayInt = (int)$today->format('d') ;
            if (($todayInt - $dayDiff->d) > (int)$jobDate->format('d')) {
                $publishDate = /*@translate*/'vor ' . ($dayDiff->days + 1) . ' Tagen';
            }
            else {
                $publishDate = /*@translate*/'vor ' . $dayDiff->days . ' Tagen';
            }

        }
        elseif ($dayDiff->days >= 30 ) {
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