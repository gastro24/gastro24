<?php

namespace Gastro24\Form\Organizations\Hydrator;

use Core\Entity\Hydrator\EntityHydrator;
use Gastro24\Entity\OrganizationAdditional;

/**
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class OrganizationDescriptionHydrator extends EntityHydrator
{
    private $organizationAdditionalRepository;

    public function __construct($organizationAdditionalRepository)
    {
        parent::__construct();
        $this->init();
        $this->organizationAdditionalRepository  = $organizationAdditionalRepository;
    }

    public function extract($object)
    {
        $data = parent::extract($object);

        /** @var \Gastro24\Entity\OrganizationAdditional $organizationAdditional */
        $organizationAdditional = $this->organizationAdditionalRepository->findOneByOrganizationId($object->getId());

        if ($organizationAdditional) {
            $data['videoLink'] = $organizationAdditional->getVideoLink();
        }

        return $data;
    }

    /**
     * @param array $data
     * @param \Organizations\Entity\Organization $object
     * @return array|object
     */
    public function hydrate(array $data, $object)
    {
        if (isset($data['videoLink'])) {

            /** @var \Gastro24\Entity\OrganizationAdditional $organizationAdditional */
            $organizationAdditional = $this->organizationAdditionalRepository->findOneByOrganizationId($object->getId());

            if (!$organizationAdditional) {
                $organizationAdditional = new OrganizationAdditional();
            }

            $organizationAdditional->setOrganizationId($object->getId())
                ->setVideoLink($data['videoLink']);

            $this->organizationAdditionalRepository->getDocumentManager()->persist($organizationAdditional);
            $this->organizationAdditionalRepository->getDocumentManager()->flush($organizationAdditional);
        }

        return parent::hydrate($data, $object);
    }
}