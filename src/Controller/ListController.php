<?php

namespace Gastro24\Controller;

use Gastro24\Entity\Hydrator\OrderHydrator;
use Gastro24\Entity\JobActivation;
use Orders\Controller\ListController as BaseController;
use Zend\View\Model\ViewModel;

/**
 * OrderController.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class ListController extends BaseController
{
    protected $jobActivationRepository;

    public function __construct($jobActivationRepository)
    {
        $this->jobActivationRepository = $jobActivationRepository;
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
}