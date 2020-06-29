<?php

namespace Gastro24\Form\Organizations\Hydrator;

use Core\Entity\Hydrator\EntityHydrator;
use Core\Entity\PermissionsInterface;
use Gastro24\Entity\OrganizationAdditional;
use Gastro24\Entity\TemplateImage;

/**
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class OrganizationBannerHydrator extends EntityHydrator
{
    private $organizationAdditionalRepository;

    private $templateImagesRepository;

    private $currentUser;

    public function __construct($organizationAdditionalRepository, $templateImagesRepository, $currentUser)
    {
        parent::__construct();
        $this->init();
        $this->organizationAdditionalRepository  = $organizationAdditionalRepository;
        $this->templateImagesRepository  = $templateImagesRepository;
        $this->currentUser  = $currentUser;
    }

    public function extract($object)
    {
        $data = [];

        /** @var \Gastro24\Entity\OrganizationAdditional $organizationAdditional */
        $organizationAdditional = $this->organizationAdditionalRepository->findOneByOrganizationId($object->getId());

        if ($organizationAdditional) {
            if (!$organizationAdditional->getBanner()) {
                return $data;
            }
            $data['banner'] = $organizationAdditional->getBanner();
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
        if (!isset($data['banner']) || UPLOAD_ERR_OK !== $data['banner']['error']) {
            return $object;
        }

        $data = $data['banner'];
        $file  = $data['tmp_name'];

        /** @var \Gastro24\Entity\OrganizationAdditional $organizationAdditional */
        $organizationAdditional = $this->organizationAdditionalRepository->findOneByOrganizationId($object->getId());

        if (!$organizationAdditional) {
            $organizationAdditional = new OrganizationAdditional();
        }

        $bannerImage = new TemplateImage();
        $bannerImage->setFile($file)
            ->setName($data['name'])
            ->setType($data['type']);
        $bannerImage->getPermissions()->grant($this->currentUser, PermissionsInterface::PERMISSION_CHANGE);

        $this->organizationAdditionalRepository->getDocumentManager()->persist($bannerImage);
        $this->organizationAdditionalRepository->getDocumentManager()->flush($bannerImage);

        $organizationAdditional->setOrganizationId($object->getId())
            ->setBanner($bannerImage);

        $this->organizationAdditionalRepository->getDocumentManager()->persist($organizationAdditional);
        $this->organizationAdditionalRepository->getDocumentManager()->flush($organizationAdditional);

        return $organizationAdditional;
    }
}