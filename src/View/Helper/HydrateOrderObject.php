<?php

namespace Gastro24\View\Helper;

use Gastro24\Entity\Hydrator\OrderHydrator;
use Zend\Form\View\Helper\AbstractHelper;

/**
 * HydrateOrderObject.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class HydrateOrderObject extends AbstractHelper
{
    /**
     * @var OrderHydrator
     */
    private $hydrator;

    public function __construct($hydrator)
    {
        $this->hydrator = $hydrator;
    }

    /**
     * @param $order
     * @return array
     */
    public function __invoke($order)
    {
        return $this->hydrator->extractInvoiceAddress($order);
    }
}