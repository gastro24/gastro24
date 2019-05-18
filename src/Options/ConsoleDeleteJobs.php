<?php

namespace Gastro24\Options;

use Core\Repository\RepositoryService;
use Zend\Stdlib\AbstractOptions;

/**
 * ConsoleDeleteJobs.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class ConsoleDeleteJobs extends AbstractOptions
{
    private $crawler;
    private $paid;
    private $single;

    /**
     * @return mixed
     */
    public function getCrawler()
    {
        return $this->crawler;
    }

    /**
     * @param mixed $crawler
     * @return ConsoleDeleteJobs
     */
    public function setCrawler($crawler)
    {
        $this->crawler = $crawler;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSingle()
    {
        return $this->single;
    }

    /**
     * @param mixed $single
     * @return ConsoleDeleteJobs
     */
    public function setSingle($single)
    {
        $this->single = $single;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaid()
    {
        return $this->paid;
    }

    /**
     * @param mixed $paid
     * @return ConsoleDeleteJobs
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;

        return $this;
    }
}