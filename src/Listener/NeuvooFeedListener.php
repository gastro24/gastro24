<?php

namespace Gastro24\Listener;

use Gastro24\Entity\UserProduct;
use Jobs\Entity\Job;
use Jobs\Listener\Events\JobEvent;

/**
 * Class NeuvooFeedListener
 * @package Gastro24\Listener
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class NeuvooFeedListener
{
    private $jobRepository;

    public function __construct($jobRepository)
    {
        $this->jobRepository = $jobRepository;
    }

    public function __invoke(JobEvent $event)
    {
        $job = $event->getJobEntity();
        $company = $job->getOrganization();
        if (!$company) {
            return;
        }

        $owner = $company->getUser();
        if (!$owner) {
            return;
        }

        $jobs = $this->jobRepository->findBy(['user' => $owner]);
        $hasNeuvooPortal = false;

        /** @var Job $jobEntity */
        foreach ($jobs as $jobEntity) {
            if (in_array('neuvoo', $jobEntity->getPortals()) !== false) {
                $hasNeuvooPortal = true;
            }
        }

        if ($hasNeuvooPortal) {
            $portals = $job->getPortals();
            $portals[] = 'neuvoo';
            $portals = array_unique($portals);
            $job->setPortals($portals);
        }

        $this->jobRepository->store($job);
    }
}