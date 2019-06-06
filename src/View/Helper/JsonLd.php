<?php

namespace Gastro24\View\Helper;

use Gastro24\Entity\Decorator\JsonLdProvider;
use Jobs\Entity\JobInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * JsonLd.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class JsonLd extends AbstractHelper
{

    /**
     * @var JobInterface
     */
    private $job;

    /**
     * Print the JSON-LD representation of a job.
     *
     * Wraps it in <script type="application/ld+json"> tag
     *
     * @param JsonLdProvider|null $job
     *
     * @return string
     */
    public function __invoke($job = null)
    {
        $job = $job ?: $this->job;

        if (null === $job) {
            return '';
        }

        $jsonLdProvider = new JsonLdProvider($job);

        return '<script type="application/ld+json">'
            . $jsonLdProvider->toJsonLd()
            . '</script>';
    }

    /**
     * Set the default job to use, if invoked without arguments.
     *
     * @param JobInterface $job
     *
     * @return JsonLd
     */
    public function setJob(JobInterface $job)
    {
        $this->job = $job;

        return $this;
    }
}