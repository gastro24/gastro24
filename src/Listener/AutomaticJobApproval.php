<?php

namespace Gastro24\Listener;

use Jobs\Entity\JobSnapshot;
use Jobs\Entity\JobSnapshotStatus;
use Jobs\Entity\Status;
use Jobs\Listener\Events\JobEvent;

/**
 * AutomaticJobApproval.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class AutomaticJobApproval
{
    private $snapshotRepository;
    private $jobActivationRepository;
    private $jobRepository;
    private $response;
    protected $jobEvents;
    protected $jobEvent;

    public function __construct($jobRepository, $snapshotRepository, $jobActivationRepository, $response, $jobEvents, $jobEvent)
    {
        $this->snapshotRepository = $snapshotRepository;
        $this->jobActivationRepository = $jobActivationRepository;
        $this->jobRepository = $jobRepository;
        $this->response = $response;
        $this->jobEvents = $jobEvents;
        $this->jobEvent = $jobEvent;
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

        /** @var \Gastro24\Entity\JobActivation $jobActivation */
        $jobActivation = $this->jobActivationRepository->findOneByUserId($owner->getId());

        if (!$jobActivation || !$jobActivation->isAutomaticJobActivation()) {
            return;
        }

        if ($job instanceOf JobSnapshot) {
            $job->getSnapshotMeta()->setStatus(JobSnapshotStatus::ACCEPTED);
            $job = $this->snapshotRepository->merge($job);
            $this->snapshotRepository->store($job);
            $job->setDateModified();
        } else {
            $job->setDatePublishStart();
        }
        $job->changeStatus(Status::ACTIVE, sprintf(/*@translate*/ "Job opening was activated automatically "));
        $this->jobRepository->store($job);

        $jobEvent = $this->jobEvent;
        $jobEvent->setJobEntity($job);
        $jobEvent->addPortal('XingVendorApi');
        $jobEvent->setTarget($this);
        $jobEvent->setName(JobEvent::EVENT_JOB_ACCEPTED);
        $this->jobEvents->trigger($jobEvent);

        $this->response->getHeaders()->addHeaderLine('Location', '/de/job');
        $this->response->setStatusCode(302);
    }
}