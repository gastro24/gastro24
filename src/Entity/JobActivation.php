<?php

namespace Gastro24\Entity;

use Core\Entity\EntityInterface;
use Core\Entity\EntityTrait;
use Core\Entity\IdentifiableEntityInterface;
use Core\Entity\IdentifiableEntityTrait;
use \Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * JobActivation.php
 *
 * @ODM\Document(collection="gastro24.orders.jobactivations")
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class JobActivation implements EntityInterface, IdentifiableEntityInterface
{
    use EntityTrait, IdentifiableEntityTrait;

    /**
     * Defines if jobs will be activated automatically.
     *
     * @ODM\Field(type="bool")
     * @var bool
     */
    protected $automaticJobActivation;

    /**
     * @ODM\Field(type="string")
     * @var string
     */
    protected $orderId;

    /**
     * @ODM\Field(type="string")
     * @var string
     */
    protected $userId;

    /**
     * @ODM\Field(type="boolean")
     * @var boolean
     */
    protected $showActivationHint = true;

    /**
     * @return bool
     */
    public function isAutomaticJobActivation()
    {
        return $this->automaticJobActivation;
    }

    /**
     * @param bool $automaticJobActivation
     * @return JobActivation
     */
    public function setAutomaticJobActivation($automaticJobActivation)
    {
        $this->automaticJobActivation = $automaticJobActivation;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param string $orderId
     * @return JobActivation
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param string $userId
     * @return JobActivation
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return bool
     */
    public function isShowActivationHint()
    {
        return $this->showActivationHint;
    }

    /**
     * @param bool $showActivationHint
     */
    public function setShowActivationHint($showActivationHint)
    {
        $this->showActivationHint = $showActivationHint;
    }


}