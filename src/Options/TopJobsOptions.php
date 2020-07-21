<?php
namespace Gastro24\Options;

use Laminas\Stdlib\AbstractOptions;

class TopJobsOptions extends AbstractOptions
{
    /**
     * @var array
     */
    private $organizations = [];

    /**
     * @var array
     */
    private $jobs = [];


    /**
     * @return array
     */
    public function getOrganizations()
    {
        return $this->organizations;
    }

    /**
     * @param array $organizations
     *
     * @return TopJobsOptions
     */
    public function setOrganizations($organizations)
    {
        $this->organizations = $organizations;

        return $this;
    }

    /**
     * @return array
     */
    public function getJobs()
    {
        return $this->jobs;
    }

    /**
     * @param array $jobs
     *
     * @return TopJobsOptions
     */
    public function setJobs($jobs)
    {
        $this->jobs = $jobs;

        return $this;
    }

    /**
     * @return array
     */
    public function getOrganizationNames()
    {
        return array_keys($this->getOrganizations());
    }

    /**
     * @return array
     */
    public function getJobIds()
    {
        return array_keys($this->getJobs());
    }
}