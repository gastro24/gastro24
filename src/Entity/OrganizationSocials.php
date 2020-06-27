<?php

namespace Gastro24\Entity;

use Core\Entity\AbstractIdentifiableHydratorAwareEntity;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Defines the contact address of an organization
 *
 * @ODM\EmbeddedDocument
 */
class OrganizationSocials extends AbstractIdentifiableHydratorAwareEntity
{
    /**
     * link facebook account
     *
     * @var string
     * @ODM\Field(type="string") */
    protected $facebook;

    /**
     * link linkedin account
     *
     * @var string
     * @ODM\Field(type="string") */
    protected $linkedin;

    /**
     * link youtube account
     *
     * @var string
     * @ODM\Field(type="string") */
    protected $youtube;

    /**
     * link instagram account
     *
     * @var string
     * @ODM\Field(type="string") */
    protected $instagram;

    /**
     * link twitter account
     *
     * @var string
     * @ODM\Field(type="string") */
    protected $twitter;

    /**
     * Sets the facebook account link
     *
     * @param string $facebook
     * @return OrganizationSocials
     */
    public function setFacebook($facebook = "")
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * Gets the facebook account link
     *
     * @return string
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Sets the linkedin account link
     *
     * @param string $linkedin
     * @return OrganizationSocials
     */
    public function setLinkedin($linkedin = "")
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    /**
     * Gets the linkedin account link
     *
     * @return string
     */
    public function getLinkedin()
    {
        return $this->linkedin;
    }

    /**
     * @return string
     */
    public function getYoutube()
    {
        return $this->youtube;
    }

    /**
     * @param string $youtube
     * @return OrganizationSocials
     */
    public function setYoutube($youtube = "")
    {
        $this->youtube = $youtube;

        return $this;
    }

    /**
     * @return string
     */
    public function getInstagram()
    {
        return $this->instagram;
    }

    /**
     * @param string $instagram
     * @return OrganizationSocials
     */
    public function setInstagram($instagram = "")
    {
        $this->instagram = $instagram;

        return $this;
    }

    /**
     * @return string
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * @param string $twitter
     * @return OrganizationSocials
     */
    public function setTwitter($twitter = "")
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function toArray()
    {
        return [
            'facebook' => $this->getFacebook(),
            'linkedin' => $this->getLinkedin(),
            'youtube' => $this->getYoutube(),
            'instagram' => $this->getInstagram(),
            'twitter' => $this->getTwitter()
        ];
    }
}