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
    public function getOrganizationNames()
    {
        return array_keys($this->getOrganizations());
    }
}