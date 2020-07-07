<?php

namespace Gastro24\View\Helper;

use Gastro24\Options\CompanyTemplatesMap;
use Laminas\Form\View\Helper\AbstractHelper;

/**
 * JobTemplate.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class JobTemplate extends AbstractHelper
{
    /**
     * @var CompanyTemplatesMap
     */
    private $templatesMap;

    public function __construct($templatesMap)
    {
        $this->templatesMap = $templatesMap;
    }

    /**
     * @param $organisation
     * @return mixed|null
     */
    public function __invoke($organisation)
    {
        return $this->templatesMap->getTemplate($organisation);
    }
}