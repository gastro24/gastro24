<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2018 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\Entity;

use Core\Entity\EntityInterface;
use Core\Entity\EntityTrait;
use Core\Entity\IdentifiableEntityInterface;
use Core\Entity\IdentifiableEntityTrait;
use \Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * ${CARET}
 *
 * @ODM\Document(collection="gastro24.templates")
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class Template implements EntityInterface, IdentifiableEntityInterface
{
    use EntityTrait, IdentifiableEntityTrait;

    /**
     *
     * @ODM\ReferenceOne(targetDocument="\Gastro24\Entity\TemplateImage", storeAs="id")
     * @var TemplateImage
     */
    private $logo;

    /**
     *
     * @ODM\ReferenceOne(targetDocument="\Gastro24\Entity\TemplateImage", storeAs="id")
     * @var TemplateImage
     */
    private $image;

    /**
     * hide banner on job template or not
     *
     * @var boolean
     * @ODM\Field(type="boolean")
     */
    private $hideBanner;

    /**
     * @return TemplateImage
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param TemplateImage $logo
     *
     * @return self
     */
    public function setLogo(TemplateImage $logo)
    {
        $this->logo = $logo;

        return $this;
    }

    public function clearLogo()
    {
        $this->logo = null;
    }

    /**
     * @return TemplateImage
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param TemplateImage $image
     *
     * @return self
     */
    public function setImage(TemplateImage $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return bool
     */
    public function getHideBanner()
    {
        return $this->hideBanner;
    }

    /**
     * @param bool $hideBanner
     *
     * @return self
     */
    public function setHideBanner($hideBanner)
    {
        $this->hideBanner = $hideBanner;

        return $this;
    }

    public function clearImage() {
        $this->image = null;
    }


}
