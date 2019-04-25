<?php

namespace Gastro24\Entity;

use \Orders\Entity\Order as BaseOrder;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Order entity
 *
 * @ODM\Document(collection="orders", repositoryClass="Gastro24\Repository\Order")
 * @ODM\HasLifecycleCallbacks
 * @ODM\Indexes({
 *      @ODM\Index(keys={
 *                  "number"="text",
 *                  "invoiceAddress.name"="text",
 *                    "invoiceAddress.company"="text",
 *                    "invoiceAddress.street"="text",
 *                    "invoiceAddress.zipCode"="text",
 *                    "invoiceAddress.city"="text",
 *                     "invoiceAddress.region"="text",
 *                     "invoiceAddress.country"="text",
 *                     "invoiceAddress.vatId"="text",
 *                     "invoiceAddress.email"="text"
 *                 }, name="fulltext")
 * })
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 * @todo write test
 */
class Order extends BaseOrder
{
    /**
     * Defines if jobs will be activated automatically.
     *
     * @ODM\EmbedOne(targetDocument="\Gastro24\Entity\JobActivation")
     * @var \Gastro24\Entity\JobActivation
     */
    protected $jobActivation;

    /**
     * @return JobActivation
     */
    public function getJobActivation()
    {
        return $this->jobActivation;
    }

    /**
     * @param JobActivation $jobActivation
     */
    public function setJobActivation($jobActivation)
    {
        $this->jobActivation = $jobActivation;
    }

}