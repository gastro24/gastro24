<?php

namespace Gastro24\Controller;

use Auth\Entity\User;
use Core\Factory\ContainerAwareInterface;
use Core\Repository\RepositoryService;
use Gastro24\View\Helper\JobboardApplyUrl;
use Interop\Container\ContainerInterface;
use Jobs\Entity\Status;
use Jobs\Form\AdminJobEdit;
use Jobs\Form\Element\StatusSelect;
use Jobs\Listener\Events\JobEvent;
use Zend\Form\FormElementManager\FormElementManagerTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * JobController.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class JobController extends AbstractActionController implements ContainerAwareInterface
{
    /**
     * @var RepositoryService
     */
    private $repositories;

    /**
     * @var array
     */
    private $options = [
        'count' => 10
    ];

    /**
     * @var FormElementManagerTrait
     */
    private $formManager;

    private $jobEvents;

    private $imageFileCacheManager;

    /** @var JobboardApplyUrl */
    private $applyUrlHelper;

    /** @var \Gastro24\View\Helper\JobUrlDelegator */
    private $urlHelper;

    public static function factory(ContainerInterface $container)
    {
        $ob = new self();
        $ob->setContainer($container);
        return $ob;
    }

    public function setContainer(ContainerInterface $container)
    {
        $this->repositories     = $container->get('repositories');
        $this->formManager      = $container->get('forms');
        $this->jobEvents        = $container->get('Jobs/Events');
        $this->imageFileCacheManager = $container->get('Organizations\ImageFileCache\Manager');
        $options = $container->get('Jobs/JobboardSearchOptions');
        $this->options = [ 'count' => $options->getPerPage() ];
        $viewHelperManager = $container->get('ViewHelperManager');
        $this->applyUrlHelper = $viewHelperManager->get('gastroApplyUrl');
        $this->urlHelper = $viewHelperManager->get('jobUrl');
    }

    public function changeStatusAction()
    {
        $repositories = $this->repositories;
        $jobs         = $repositories->get('Jobs');
        $job          = $jobs->find($this->params()->fromQuery('id'));
        $forms        = $this->formManager;
        /** @var AdminJobEdit $form */
        $form         = $forms->get('Jobs/AdminJobEdit');
        $request      = $this->getRequest();

        $form->remove('datePublishStart');
        $form->setIsDescriptionsEnabled(false);

        /** @var StatusSelect $statusElement */
        $valueOptions = [
            Status::ACTIVE => /*@translate*/ 'Active',
            Status::INACTIVE => /*@translate*/ 'Inactive',
        ];
        $statusElement = $form->get('status');
        $statusElement->setValueOptions($valueOptions);

        if ($request->isPost()) {
            $post = $this->params()->fromPost();
            $form->setData($post);
            $valid = $form->isValid();
            $errors = $form->getMessages();

            if ($valid) {
                //$job->setDatePublishStart($post['datePublishStart']);
                if ($job->getStatus()->getName() != $post['status']) {
                    $oldStatus = $job->getStatus();
                    $job->changeStatus($post['status'], '[System] Status changed by abo user.');
                    $events = $this->jobEvents;
                    $events->trigger(JobEvent::EVENT_STATUS_CHANGED, $this, [ 'job' => $job, 'status' => $oldStatus ]);
                }
            }

            return new JsonModel([
                'valid' => $valid,
                'errors' => $errors
            ]);
        }

        $form->bind($job);

        return [ 'form' => $form, 'job' => $job ];
    }

    /**
     * Handles the dashboard widget for the jobs module.
     *
     * @return array
     */
    public function dashboardAction()
    {
        $repositories = $this->repositories;
        /* @var $request \Zend\Http\Request */
        $request     = $this->getRequest();
        $params      = $request->getQuery();
        $isRecruiter = $this->Acl()->isRole(User::ROLE_RECRUITER);
        $jobs        = $repositories->get('Jobs');

        if ($isRecruiter) {
            $params->set('by', 'me');
        }

        $params['sort'] = '-dateCreated.date';

        $paginator = $this->paginator('Jobs/Job', $params);

        return [
            'script' => 'jobs/index/dashboard',
            'type'   => $this->params('type'),
            'myJobs' => $jobs,
            'jobs'   => $paginator
        ];
    }

    /**
     * Lists all jobs in favorite list.
     */
    public function savedJobsAction()
    {
        $routeParams = $this->params()->fromRoute();
        $queryParams = $this->params()->fromQuery();
        if (isset($routeParams['q']) && !isset($getParams['q'])) {
            $getParams['q']=$routeParams['q'];
        }

        if (isset($queryParams['remove']) && $queryParams['remove']) {
            $container = new Container('gastro24_savedjobs');
            if (isset($queryParams['id'])) {
                // remove single job
                unset($container->jobs[$queryParams['id']]);
            }
            else {
                // clear session
                $container->exchangeArray([]);
            }
        }

        $result = $this->pagination([
            'params' => ['Jobs_Board', [
                'q',
                'count' => $this->options['count'],
                'page' => 1,
                'l',
                'd' => 10]
            ],
            'form' => ['as' => 'filterForm', 'Jobs/JobboardSearch'],
            'paginator' => ['as' => 'jobs', 'Jobs/Board']
        ]);

        $organizationImageCache = $this->imageFileCacheManager;

        $result['organizationImageCache'] = $organizationImageCache;

        return new ViewModel($result);
    }

    /**
     * Saves single job to session favorite list.
     *
     */
    public function saveJobAction()
    {
        /* @var $request \Zend\Http\Request */
        $request     = $this->getRequest();
        /* @var \Zend\Http\PhpEnvironment\Response $response */
        $response = $this->getResponse();

        $jobId = $this->params()->fromRoute('id');
        $action = $request->getPost('action');
        $jobs = $this->repositories->get('Jobs');
        /** @var \Jobs\Entity\Job $job */
        $job = $jobs->find($jobId);

        $container = new Container('gastro24_savedjobs');
        if (!isset($container->jobs)) {
            $container->jobs = [];
        }

        if ($action == 'remove') {
            unset($container->jobs[$jobId]);
        }
        else {
            $createdAt = $job->getDatePublishStart() ? $job->getDatePublishStart()->format('d.m.Y') :
                $job->getDateCreated()->format('d.m.Y') ;

            $organization = $job->getOrganization();
            $orgName = '';
            if ($organization && $organization->getOrganizationName() && $organization->getOrganizationName()->getName()) {
                $orgName = $organization->getOrganizationName()->getName();
            } elseif ($job->getCompany()) {
                $orgName = $job->getCompany();
            }

            $location = (!$job->getLocation() && !$job->getLocations()) ? /*@translate*/'Swiss' :
                preg_replace('~\(.*?\)$~', '', (string) $job->getLocation());

            $container->jobs[$jobId] = [
                'id' => $jobId,
                'title' => $job->getTitle(),
                'url' => $this->urlHelper->__invoke($job, ['linkOnly' => true ]),
                'organizationName' => $orgName,
                'location' => $location,
                'employmentType' => $job->getClassifications()->getEmploymentTypes()->__toString() ?: /*@translate*/'Vollzeit',
                'createdAt' => $createdAt,
                'applyUrl' => $this->applyUrlHelper->__invoke($job),
            ];
        }
        $response->getHeaders()->addHeaderLine( 'Content-Type', 'application/json' );

        $result = [
            'id' => $jobId,
            'action' => $action,
            'jobsCount' => count($container->jobs),
            'success' => true
        ];
        $response->setContent(json_encode($result));

        return $response;
    }
}