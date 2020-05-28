<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2018 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\Listener;

use Core\Entity\Permissions;
use Core\Listener\Events\AjaxEvent;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Gastro24\Entity\TemplateImage;
use Gastro24\Form\JobDetailsForm;
use Gastro24\Form\SingleJobForm;
use Jobs\Entity\Job;
use Zend\Form\FormElementManager\FormElementManagerV3Polyfill as FormElementManager;
use Zend\Stdlib\ArrayUtils;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class JobFileUpload
{
    /**
     * @var FormElementManager
     */
    private $formManager;

    /**
     * @var JobDetailsForm
     */
    private $jobDetailsForm;

    /**
     * @var SingleJobForm
     */
    private $singleJobForm;

    private $repository;
    private $jobRepository;

    public function __construct(FormElementManager $formManager, DocumentRepository $repository, $jobRepository)
    {
        //$this->form = $form;
        $this->formManager = $formManager;
        $this->repository = $repository;
        $this->jobRepository = $jobRepository;
    }

    public function __invoke(AjaxEvent $event)
    {
        // details fieldset (i.e. in admin dashboard)
        if (isset($_POST['details'])) {
            $mode = isset($_POST['details']['mode']) ? $_POST['details']['mode'] : null;

            if (!$mode) {
                return;
            }

            $files = $event->getRequest()->getFiles()->toArray();
            $data = ArrayUtils::merge($_POST, $files);
            $this->jobDetailsForm = $this->formManager->get(JobDetailsForm::class);
            $this->jobDetailsForm->setName('details');

            if (isset($files['details']['pdf'])) {
                return $this->uploadPdf($event, $data);
            }

            return $this->uploadTemplateImage($event, $data);
        }
        // single job - file uploads
        else {
            $files = $event->getRequest()->getFiles()->toArray();
            $data = ArrayUtils::merge($_POST, $files);
            $this->singleJobForm = $this->formManager->get(SingleJobForm::class);

            if (isset($files['pdf'])) {
                return $this->uploadPdfForSingleForm($event, $data);
            }

            return $this->uploadTemplateImageForSingleForm($event, $data);
        }
    }

    public function uploadPdf($event, $data)
    {
        $this->jobDetailsForm->setData($data);
        $this->jobDetailsForm->setValidationGroup(['details' => ['mode', 'pdf']]);

        if ($this->jobDetailsForm->isValid()) {
            $values = $this->jobDetailsForm->getData();
            return ['valid' => true, 'content' => '
                <a href="' . $values['details']['pdf']['uri'] . '" target="_blank">' . basename($values['details']['pdf']['uri']) . '</a>
                <a href="?ajax=jobdetailsdelete&file=' .basename($values['details']['pdf']['uri']). '" class="file-delete btn btn-default btn-xs">
        <span class="yk-icon yk-icon-minus"></span>
    </a>
    <input type="hidden" value="' . $values['details']['pdf']['uri'] . '" name="pdf_uri">'
            ];
        }

        $values = $this->jobDetailsForm->getData();
        @unlink($values['details']['pdf']['tmp_name']);
        return ['valid' => false, 'errors' => $this->jobDetailsForm->getMessages()];
    }

    public function uploadPdfForSingleForm($event, $data)
    {
        $this->singleJobForm->setData($data);
        $this->singleJobForm->setValidationGroup(['pdf']);

        if ($this->singleJobForm->isValid()) {
            $values = $this->singleJobForm->getData();
            return [
                'valid' => true,
                'content' => '
                <a href="' . $values['pdf']['uri'] . '" target="_blank">' . basename($values['pdf']['uri']) . '</a>
                <a href="?ajax=jobdetailsdelete&file=' . basename($values['pdf']['uri']) . '" class="file-delete btn btn-default btn-xs">
        <span class="yk-icon yk-icon-minus"></span>
    </a>
    <input type="hidden" value="' . $values['pdf']['uri'] . '" name="pdf_uri">'
            ];
        }

        $values = $this->singleJobForm->getData();
        @unlink($values['pdf']['tmp_name']);

        return [
            'valid' => false,
            'errors' => $this->singleJobForm->getMessages()
        ];
    }

    public function deletePdfFile(AjaxEvent $event)
    {
        $file = $event->getRequest()->getQuery('file');
        @unlink('public/static/Jobs/' . $file);

        return ['ok' => true];
    }

    public function uploadTemplateImage($event, $data)
    {
        $name = isset($data['details']['logo']) && !empty($data['details']['logo']) ? 'logo' : 'image';
        $this->jobDetailsForm->setData($data);
        $this->jobDetailsForm->setValidationGroup(['details' => ['mode', $name]]);

        if ($this->jobDetailsForm->isValid()) {
            $values = $this->jobDetailsForm->getData();
            /** @var TemplateImage $file */
            $file = $values['details'][$name]['entity'];

            if (!isset($data['job'])) {
                $filePermissions = $file->getPermissions();
                $filePermissions->grant('all', Permissions::PERMISSION_CHANGE);

                return [
                    'valid' => true,
                    'content' => '
                    <a href="/' . $file->getUri() . '" target="_blank">'
                        . $file->getName() . '</a>'
                        . '<a href="/' . $file->getUri() . '?do=delete" class="file-delete btn btn-default btn-xs">
                    <span class="yk-icon yk-icon-minus"></span>
                        </a>
                        <input type="hidden" value="' . $file->getId() . '" name="details[' . $name . '_id]">
                        <input type="hidden" value="' . $file->getUri() . '" name="details[' . $name . '_url]">'
                ];
            }

            // grant change permission for file
            /** @var Job $job */
            $job = $this->jobRepository->findOneById($data['job']);
            $filePermissions = $file->getPermissions();
            $filePermissions->grant($job->getUser(), Permissions::PERMISSION_CHANGE);

            return [
                'valid' => true,
                'content' => '
                    <a href="/' . $file->getUri() . '" target="_blank">'
                    . $file->getName() . '</a>'
                    . '<a href="/' . $file->getUri() . '?do=delete" class="file-delete btn btn-default btn-xs">
                    <span class="yk-icon yk-icon-minus"></span>
                        </a>
                        <input type="hidden" value="' . $file->getId() . '" name="details[' . $name . '_id]">'
            ];
        }

        $values = $this->jobDetailsForm->getData();
        if (isset($values['details'][$name]['entity']) && !empty($values['details'][$name]['entity'])) {
            $this->repository->getDocumentManager()->remove($values['details'][$name]['entity']);
        }

        return ['valid' => false, 'errors' => $this->jobDetailsForm->getMessages() ];
    }

    public function uploadTemplateImageForSingleForm($event, $data)
    {
        $name = isset($data['logo']) && !empty($data['logo']) ? 'logo' : 'bannerImage';
        $this->singleJobForm->setData($data);
        $this->singleJobForm->setValidationGroup([$name]);

        if ($this->singleJobForm->isValid()) {
            $values = $this->singleJobForm->getData();
            /** @var TemplateImage $file */
            $file = $values[$name]['entity'];

            $filePermissions = $file->getPermissions();
            $filePermissions->grant('all', Permissions::PERMISSION_CHANGE);

            return [
                'valid' => true,
                'content' => '
                <a href="/' . $file->getUri() . '" target="_blank">'
                    . $file->getName() . '</a>'
                    . '<a href="/' . $file->getUri() . '?do=delete" class="file-delete btn btn-default btn-xs">
                <span class="yk-icon yk-icon-minus"></span>
                    </a>
                    <input type="hidden" value="' . $file->getId() . '" name="' . $name . '_id">
                    <input type="hidden" value="' . $file->getUri() . '" name="' . $name . '_url">'
            ];
        }

        $values = $this->singleJobForm->getData();
        if (isset($values[$name]['entity']) && !empty($values[$name]['entity'])) {
            $this->repository->getDocumentManager()->remove($values[$name]['entity']);
        }

        return ['valid' => false, 'errors' => $this->singleJobForm->getMessages() ];
    }
}
