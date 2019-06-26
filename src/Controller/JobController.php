<?php

namespace Gastro24\Controller;

use Auth\Entity\User;
use Core\Factory\ContainerAwareInterface;
use Core\Repository\RepositoryService;
use Interop\Container\ContainerInterface;
use Jobs\Entity\Status;
use Jobs\Form\AdminJobEdit;
use Jobs\Form\Element\StatusSelect;
use Jobs\Listener\Events\JobEvent;
use Zend\Form\FormElementManager\FormElementManagerTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

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
     * @var FormElementManagerTrait
     */
    private $formManager;

    private $jobEvents;

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
}