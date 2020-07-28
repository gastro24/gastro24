<?php

namespace Gastro24\View\Helper;

use Gastro24\Entity\Decorator\JsonLdProvider;
use Jobs\Entity\JobInterface;
use Laminas\View\Helper\AbstractHelper;

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
     * @var \Orders\Repository\Orders
     */
    private $ordersRepository;

    public function __construct($ordersRepository)
    {
        $this->ordersRepository = $ordersRepository;
    }

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

        // TODO: order should be placed diretly after abo was created
        // currently after first job was created
        $order = $this->ordersRepository->findByJobId($job->getId());
        $invoiceAddress = null;
        if ($order) {
            $invoiceAddress = $order->getInvoiceAddress();
        }
        $jsonLdProvider = new JsonLdProvider($job, $invoiceAddress);

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