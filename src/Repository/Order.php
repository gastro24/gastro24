<?php

namespace Gastro24\Repository;

use Core\Entity\Hydrator\EntityHydrator;
use Core\Repository\SnapshotRepository;
use \Orders\Repository\Orders as BaseRepository;
use Zend\Hydrator\HydratorInterface;

/**
 * Orders.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class Order extends BaseRepository
{

    /**
     * @var HydratorInterface
     */
    protected $hydrator;

    /**
     * @param \Zend\Hydrator\HydratorInterface $hydrator
     *
     * @return self
     */
    public function setHydrator($hydrator)
    {
        $this->hydrator = $hydrator;

        return $this;
    }

    /**
     * @return \Zend\Hydrator\HydratorInterface
     */
    public function getHydrator()
    {
        if (!$this->hydrator) {
            $this->setHydrator(new EntityHydrator());
        }
        return $this->hydrator;
    }
}