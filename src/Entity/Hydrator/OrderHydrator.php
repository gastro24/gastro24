<?php

namespace Gastro24\Entity\Hydrator;

use Core\Entity\Hydrator\EntityHydrator;

/**
 * OrderHydrator.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class OrderHydrator extends EntityHydrator
{
    protected $jobActivationRepository;

    public function __construct($jobActivationRepository)
    {
        $this->jobActivationRepository = $jobActivationRepository;
        parent::__construct();
    }

    /**
     * @param \Gastro24\Entity\Order $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'automaticJobActivation' => false
        ];

        /** @var \Gastro24\Entity\JobActivation $jobActivation */
        $jobActivation = $this->jobActivationRepository->findOneByOrderId($object->getId());

        if ($jobActivation) {
            $data['automaticJobActivation'] = $jobActivation->isAutomaticJobActivation();
        }

        return $data;
    }
}