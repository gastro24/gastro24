<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2018 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\Form;

use Gastro24\Entity\Template;
use Gastro24\Entity\TemplateImage;
use Jobs\Entity\JobSnapshot;
use Laminas\Hydrator\HydratorInterface;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class JobDetailsHydrator implements HydratorInterface
{
    private $repositories;

    public function __construct($repositories)
    {
        $this->repositories = $repositories;
    }

    /**
     * @param JobSnapshot $object
     * @return array
     */
    public function extract($object)
    {
        $link = $object->getLink();
        if ('.pdf' == substr($link, -4)) {
            $mode = 'pdf';
        } else if ($link) {
            $mode = 'uri';
        } else {
            $mode = 'html';
        }

        /** @var Template $template */
        $template = $object->getAttachedEntity('gastro24-template');
        $image    = $template && ($image = $template->getImage()) ? $image->getUri() : null;
        $logo    = $template && ($logo = $template->getLogo()) ? $logo->getUri() : null;
        $hideBanner = $template ? $template->getHideBanner() : false;

        return [
            'mode' => $mode,
            'uri' => $mode == 'uri' ? $link : '',
            'pdf' => $mode == 'pdf' ? $link : '',
//            'description' => $object->getTemplateValues()->getDescription(),
//            'introduction' => $object->getTemplateValues()->getIntroduction(),
//            'benefits' => $object->getTemplateValues()->getBenefits(),
            'position' => $object->getTemplateValues()->get('position'),
            'companyWebsite' => $object->getTemplateValues()->get('companyWebsite'),
            'companyDescription' => $object->getTemplateValues()->get('companyDescription'),
//            'requirements' => $object->getTemplateValues()->getRequirements(),
            'image' => $image,
            'logo' => $logo,
            'hideBanner' => $hideBanner,
        ];
    }

    public function hydrate(array $data, $object)
    {
        /* @var \Jobs\Entity\Job $object */
        if ('html' == $data['mode']) {
            $link = $object->getLink();
            if ('.pdf' == substr($link, -4)) {
                @unlink('public/' . $link);
            }
            $object->setLink('');
//            $object->getTemplateValues()->setIntroduction($data['introduction']);
//            $object->getTemplateValues()->setDescription($data['description']);
//            $object->getTemplateValues()->setBenefits($data['benefits']);
            $object->getTemplateValues()->position = $data['position'];
            $object->getTemplateValues()->companyWebsite = $data['companyWebsite'];
            $object->getTemplateValues()->companyDescription = $data['companyDescription'];
//            $object->getTemplateValues()->setRequirements($data['requirements']);

        } else {
            $object->setLink('uri' == $data['mode'] ? $data['uri'] : (isset($_POST['pdf_uri']) ? $_POST['pdf_uri'] : $data['pdf']));
        }

        $template = $object->getAttachedEntity('gastro24-template');
        $repository = $this->repositories->get(TemplateImage::class);
        if (!$template) {
            $template = new Template();
            $this->repositories->store($template);
            $object->addAttachedEntity($template, 'gastro24-template');
        }
        if (isset($_POST['details']['logo_id'])) {
            $file = $repository->find($_POST['details']['logo_id']);
            $template->setLogo($file);
        }
        if (isset($_POST['details']['image_id'])) {
            $file = $repository->find($_POST['details']['image_id']);
            $template->setImage($file);
        }
        $template->setHideBanner($_POST['details']['hideBanner']);

        return $object;
    }

}
