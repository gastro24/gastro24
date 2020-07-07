<?php

namespace Gastro24\Controller;

use Auth\Entity\User;
use Gastro24\Entity\Hydrator\OrderHydrator;
use Gastro24\Entity\JobActivation;
use Jobs\Controller\ManageController;
use Jobs\Entity\JobSnapshot;
use Jobs\Entity\JobSnapshotStatus;
use Jobs\Entity\Status;
use Jobs\Listener\Events\JobEvent;
use Laminas\EventManager\EventInterface;
use Laminas\EventManager\EventManagerInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\ViewModel;
use Orders\Controller\ListController as BaseController;

/**
 * OrdersController.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class OrdersController extends BaseController
{
    protected $jobActivationRepository;
    protected $orderRepository;
    protected $snapshotRepository;
    protected $jobsRepository;
    protected $jobEvents;
    protected $jobEvent;

    public function __construct(
        $jobActivationRepository,
        $orderRepository,
        $snapshotRepository,
        $jobsRepository,
        EventManagerInterface $jobEvents,
        EventInterface $jobEvent
    )
    {
        $this->jobActivationRepository = $jobActivationRepository;
        $this->orderRepository = $orderRepository;
        $this->snapshotRepository = $snapshotRepository;
        $this->jobsRepository = $jobsRepository;
        $this->jobEvents = $jobEvents;
        $this->jobEvent = $jobEvent;
    }

    /**
     * @return $this|void
     */
    public function attachDefaultListeners()
    {
        parent::attachDefaultListeners();
        $events = $this->getEventManager();
        $events->attach(MvcEvent::EVENT_DISPATCH, array($this, 'preDispatch'), 10);
    }

    /**
     * Dispatch listener callback.
     *
     * Attaches the MailSender aggregate listener to the job event manager.
     *
     * @param MvcEvent $e
     * @since 0.19
     */
    public function preDispatch(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();
        $action = $routeMatch->getParam('action');
        $services = $e->getApplication()->getServiceManager();
        if (in_array($action, array('jobactivation'))) {
            $jobEvents = $services->get('Jobs/Events');
            $mailSender = $services->get('Jobs/Listener/MailSender');

            $mailSender->attach($jobEvents);
        }
    }

    public function indexAction()
    {
        $results = $this->pagination([
            'form' => ['Core/Search', 'as' => 'form'],
            'paginator' => ['Orders', [ 'sort' => 'date'], 'as' => 'orders']
        ]);

        $results['hydrator'] = new OrderHydrator($this->jobActivationRepository);

        $model = new ViewModel($results);

        return $model;
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

            $this->jobEvent->addPortal('XingVendorApi');
            $this->jobEvent->setTarget($this);
            $this->jobEvent->setJobEntity($job);
            $this->jobEvent->setName(JobEvent::EVENT_JOB_ACCEPTED);
            $this->jobEvents->trigger($this->jobEvent);
        }
    }

}