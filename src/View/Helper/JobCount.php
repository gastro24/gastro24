<?php

namespace Gastro24\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

/**
 * JobCount.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class JobCount extends AbstractHelper
{
    /**
     * @var $paginator
     */
    private $paginator;

    public function __construct($paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * @return int
     */
    public function __invoke()
    {
        return $this->paginator->getTotalItemCount();
    }
}