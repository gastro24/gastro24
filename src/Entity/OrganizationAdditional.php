<?php

namespace Gastro24\Entity;

use Core\Entity\EntityInterface;
use Core\Entity\EntityTrait;
use Core\Entity\IdentifiableEntityInterface;
use Core\Entity\IdentifiableEntityTrait;
use \Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * ${CARET}
 *
 * @ODM\Document(collection="gastro24.organizations.additional")
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class OrganizationAdditional implements EntityInterface, IdentifiableEntityInterface
{
    use EntityTrait, IdentifiableEntityTrait;

    /**
     * @ODM\Field(type="string")
     * @var string
     */
    protected $organizationId;

    /**
     * Video link of organization
     *
     * @var string
     * @ODM\Field(type="string")
     */
    protected $videoLink;

    /**
     * Organization socials data.
     *
     * @ODM\EmbedOne(targetDocument="\Gastro24\Entity\OrganizationSocials")
     */
    protected $socials;

    /**
     * @ODM\ReferenceOne(targetDocument="\Gastro24\Entity\TemplateImage", storeAs="id")
     * @var TemplateImage
     */
    protected $banner;


    /**
     * @return string
     */
    public function getOrganizationId()
    {
        return $this->organizationId;
    }

    /**
     * @param string $organizationId
     * @return OrganizationAdditional
     */
    public function setOrganizationId($organizationId)
    {
        $this->organizationId = $organizationId;

        return $this;
    }

    /**
     * Gets video link of organization
     *
     * @return string
     */
    public function getVideoLink()
    {
        return $this->videoLink;
    }

    /**
     * Set video link of organization
     *
     * @param string $videoLink
     *
     * @return $this
     */
    public function setVideoLink($videoLink)
    {
        $this->videoLink = $videoLink;

        return $this;
    }

    /**
     * Sets the socials of an organization
     *
     * @param EntityInterface $contact
     *
     * @return $this
     */
    public function setSocials(EntityInterface $socials = null)
    {
        if (!$socials instanceof OrganizationSocials) {
            $socials = new OrganizationSocials();
        }
        $this->socials = $socials;

        return $this;
    }

    /**
     * Gets the socials of an organization
     *
     * @return OrganizationSocials
     */
    public function getSocials()
    {
        if (!$this->socials instanceof OrganizationSocials) {
            $this->socials = new OrganizationSocials();
        }

        return $this->socials;
    }

    /**
     * @return TemplateImage
     */
    public function getBanner()
    {
        return $this->banner;
    }

    /**
     * @param TemplateImage $image
     *
     * @return self
     */
    public function setBanner(TemplateImage $image)
    {
        $this->banner = $image;

        return $this;
    }

    public function clearBanner() {
        $this->banner = null;
    }

}