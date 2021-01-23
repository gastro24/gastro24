<?php

namespace Gastro24\View\Helper\Jobs;

use Laminas\Form\View\Helper\AbstractHelper;

/**
 * Class CompanyJobsCount
 * @package Gastro24\View\Helper\Jobs
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class CompanyJobsCount extends AbstractHelper
{

    /**
     * @var $paginators
     */
    private $paginators;

    public function __construct($paginators)
    {
        $this->paginators = $paginators;
    }

    /**
     * @param \Jobs\Entity\Job $currentJob
     * @return array
     */
    public function __invoke($companyId)
    {
        $boardParams = [
            'organization_id' => $companyId,
        ];
        $paginator  = $this->paginators->get('Organizations/ListJob', $boardParams);

        return $paginator->getTotalItemCount();
    }
}