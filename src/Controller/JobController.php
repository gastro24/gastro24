<?php

namespace Gastro24\Controller;

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
}