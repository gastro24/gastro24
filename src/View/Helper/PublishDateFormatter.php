<?php

namespace Gastro24\View\Helper;

use DateTime;
use Jobs\Entity\Job;
use Zend\Form\View\Helper\AbstractHelper;

/**
 * PublishDateFormatter.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class PublishDateFormatter extends AbstractHelper
{
    private $jobsRepository;

    public function __construct($jobsRepository)
    {
        $this->jobsRepository = $jobsRepository;
    }

    /**
     * @param Job $org
     * @return bool
     */
    public function __invoke($job)
    {
        // get date difference
        $today = new DateTime();
        if ($job instanceof \Solr\Entity\JobProxy) {
            $jobObject = $this->jobsRepository->find($job->getId());
            $jobDate = $jobObject->getDatePublishStart() ?? $jobObject->getDateCreated();
        }
        else {
            $jobDate = $job->getDatePublishStart() ?? $job->getDateCreated();
        }

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
            if ($dayDiff->i == 1) {
                $publishDate = /*@translate*/'vor 1 Minute';
            }
            else {
                $publishDate = /*@translate*/'vor ' . $dayDiff->i . ' Minuten';
            }
        }
        elseif ($dayDiff->days < 1) {
            if ($today->format('d') !== $jobDate->format('d')) {
                $publishDate = /*@translate*/'Gestern';
            }
            elseif ($dayDiff->h == 1) {
                $publishDate = /*@translate*/'vor 1 Stunde';
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