<?php

namespace Gastro24\Form\Organizations\Hydrator;

use Core\Entity\Hydrator\EntityHydrator;
use Gastro24\Entity\OrganizationAdditional;

/**
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class OrganizationSocialHydrator extends EntityHydrator
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
            $socials = $organizationAdditional->getSocials();
            $data = array_merge($data, $socials->toArray());
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
        if (count($data)) {
            /** @var \Gastro24\Entity\OrganizationAdditional $organizationAdditional */
            $organizationAdditional = $this->organizationAdditionalRepository->findOneByOrganizationId($object->getId());

            if (!$organizationAdditional) {
                $organizationAdditional = new OrganizationAdditional();
            }

            $organizationAdditional->setOrganizationId($object->getId());
            $socials = $organizationAdditional->getSocials();

            foreach ($data as $attribute => $value) {
                $propertyName = ucfirst($attribute);
                $setterFunction = 'set' . $propertyName;
                $socials->$setterFunction($value);
            }
            $organizationAdditional->setSocials($socials);

            $this->organizationAdditionalRepository->getDocumentManager()->persist($organizationAdditional);
            $this->organizationAdditionalRepository->getDocumentManager()->flush($organizationAdditional);
        }

        return parent::hydrate($data, $object);
    }
}