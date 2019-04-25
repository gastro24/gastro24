<?php

namespace Gastro24\Controller;

use Gastro24\Entity\JobActivation;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * OrdersController.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class OrdersController extends AbstractActionController
{
    protected $jobActivationRepository;

    public function __construct($jobActivationRepository)
    {
        $this->jobActivationRepository = $jobActivationRepository;
    }

    public function jobactivationAction()
    {
        $orderId = $this->params()->fromRoute('id');

        $jobActivation = $this->jobActivationRepository->findOneByOrderId($orderId);

        if (!$jobActivation) {
            $jobActivation = new JobActivation();
            $jobActivation->setOrderId($orderId);
        }

        //$jobActivation = $order->getJobActivation();
        $jobActivation->setAutomaticJobActivation(true);

        $this->jobActivationRepository->getDocumentManager()->persist($jobActivation);
        $this->jobActivationRepository->getDocumentManager()->flush($jobActivation);

        $this->notification()->success(/*@translate*/ 'Automatische Jobfreischaltung wurde aktiviert');

        return $this->redirect()->toRoute('lang/orders-list');
    }

}