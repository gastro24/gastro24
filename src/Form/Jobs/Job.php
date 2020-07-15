<?php
namespace Gastro24\Form\Jobs;

use Jobs\Form\Job as BaseJobForm;


class Job extends BaseJobForm
{
    /**
     * {@inheritDoc}
     */
    public function init()
    {
        parent::init();
        $this->get('general')->disableForm('salaryForm');
        $this->get('general')->disableForm('customerNote');
        $this->get('general')->get('nameForm')->setLabel('Firmenprofil');
        $this->get('general')->get('nameForm')->get('jobCompanyName')->get('companyId')->setLabel('Firmenprofil auswählen');
    }
}