<?php

namespace Gastro24\Entity\Hydrator;

use Core\Entity\Hydrator\EntityHydrator;
use Orders\Entity\InvoiceAddress;

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
            'automaticJobActivation' => false,
            'singleJob' => false
        ];

        $jobEntity = $object->getEntity()->getEntity();
        if (!$jobEntity) {
            return $data;
        }

        $user = $jobEntity->getUser();
        if (!$user) {
            $data['singleJob'] = true;
            return $data;
        }
        /** @var \Gastro24\Entity\JobActivation $jobActivation */
        $jobActivation = $this->jobActivationRepository->findOneByUserId($user->getId());

        if ($jobActivation) {
            $data['automaticJobActivation'] = $jobActivation->isAutomaticJobActivation();
        }

        return $data;
    }

    /**
     * @param \Gastro24\Entity\Order $object
     * @return array
     */
    public function extractInvoiceAddress($object)
    {
        $data = [
            'userInvoiceAdress' => null
        ];

        $jobEntity = $object->getEntity()->getEntity();
        if (!$jobEntity) {
            return $data;
        }

        $user = $jobEntity->getUser();
        if (!$user) {
            return $data;
        }

        $orderSettings = $user->getSettings('Orders');
        $invoiceAddress = $orderSettings->getInvoiceAddress();

        $invoiceAddressEntity = $this->createInvoiceAddressEntity($invoiceAddress);

        $data = [
            'userInvoiceAdress' => $invoiceAddressEntity
        ];

        return $data;
    }

    private function createInvoiceAddressEntity($invoiceAddress)
    {
        $object = new InvoiceAddress();
        $object
            ->setGender($invoiceAddress->getGender())
            ->setName($invoiceAddress->getName())
            ->setCompany($invoiceAddress->getCompany())
            ->setStreet($invoiceAddress->getStreet() . ' ' . $invoiceAddress->getHouseNumber())
            ->setZipCode($invoiceAddress->getPostalcode())
            ->setCity($invoiceAddress->getCity())
            ->setEmail($invoiceAddress->getEmail());

        return $object;
    }
}