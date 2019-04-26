<?php

namespace Gastro24\Controller;

use Auth\Entity\User;
use Gastro24\Entity\JobActivation;
use Jobs\Entity\JobSnapshot;
use Jobs\Entity\JobSnapshotStatus;
use Jobs\Entity\Status;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * OrdersController.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class OrdersController extends AbstractActionController
{
    protected $jobActivationRepository;
    protected $orderRepository;
    private $snapshotRepository;
    private $jobsRepository;

    public function __construct($jobActivationRepository, $orderRepository, $snapshotRepository, $jobsRepository)
    {
        $this->jobActivationRepository = $jobActivationRepository;
        $this->orderRepository = $orderRepository;
        $this->snapshotRepository = $snapshotRepository;
        $this->jobsRepository = $jobsRepository;
    }

    public function jobactivationAction()
    {
        $orderId = $this->params()->fromRoute('id');

        $order = $this->orderRepository->findOneById($orderId);

        $jobEntity = $order->getEntity()->getEntity();
        if (!$jobEntity) {
            return $this->redirect()->toRoute('lang/orders-list');
        }

        /** @var User $user */
        $user = $jobEntity->getUser();
        if (!$user) {
            return $this->redirect()->toRoute('lang/orders-list');
        }

        $jobActivation = $this->jobActivationRepository->findOneByUserId($user->getId());

        if (!$jobActivation) {
            $jobActivation = new JobActivation();
            $jobActivation->setOrderId($orderId)
                ->setUserId($user->getId());
        }

        $jobActivation->setAutomaticJobActivation(true);

        $this->jobActivationRepository->getDocumentManager()->persist($jobActivation);
        $this->jobActivationRepository->getDocumentManager()->flush($jobActivation);

        // activate all existing jobs
        $jobs = $this->jobsRepository->findBy(['user' => $user]);
        $this->activateJobs($jobs);

        $this->notification()->success(sprintf(/*@translate*/ 'Automatische Jobfreischaltung wurde für "%s" aktiviert',
            $user->getInfo()->getDisplayName()));

        return $this->redirect()->toRoute('lang/orders-list');
    }

    private function activateJobs($jobs)
    {
        foreach ($jobs as $job) {
            if ($job instanceOf JobSnapshot) {
                $job->getSnapshotMeta()->setStatus(JobSnapshotStatus::ACCEPTED);
                $job = $this->snapshotRepository->merge($job);
                $this->snapshotRepository->store($job);
                $job->setDateModified();
            } else {
                $job->setDatePublishStart();
            }
            $job->changeStatus(Status::ACTIVE, sprintf(/*@translate*/ "Job opening was activated automatically "));
            $this->jobsRepository->store($job);
        }
    }

}