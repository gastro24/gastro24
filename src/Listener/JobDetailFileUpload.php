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
use Jobs\Entity\Job;
use Zend\Stdlib\ArrayUtils;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class JobDetailFileUpload 
{

    private $form;

    private $repository;
    private $jobRepository;

    public function __construct(JobDetailsForm $form, DocumentRepository $repository, $jobRepository)
    {
        $this->form = $form;
        $this->repository = $repository;
        $this->jobRepository = $jobRepository;
    }

    public function __invoke(AjaxEvent $event)
    {
        $mode    = isset($_POST['details']['mode']) ? $_POST['details']['mode'] : null;

        if (!$mode) {
            return;
        }

        $files   = $event->getRequest()->getFiles()->toArray();
        $data    = ArrayUtils::merge($_POST, $files);


        if (isset($files['details']['pdf'])) {
            return $this->uploadPdf($event, $data);
        }

        return $this->uploadTemplateImage($event, $data);
    }

    public function uploadPdf($event, $data)
    {
        $this->form->setData($data);
        $this->form->setValidationGroup(['details' => ['mode', 'pdf']]);

        if ($this->form->isValid()) {
            $values = $this->form->getData();
            return ['valid' => true, 'content' => '
                <a href="' . $values['details']['pdf']['uri'] . '" target="_blank">' . basename($values['details']['pdf']['uri']) . '</a>
                <a href="?ajax=jobdetailsdelete&file=' .basename($values['details']['pdf']['uri']). '" class="file-delete btn btn-default btn-xs">
        <span class="yk-icon yk-icon-minus"></span>
    </a>
    <input type="hidden" value="' . $values['details']['pdf']['uri'] . '" name="pdf_uri">'
            ];
        }

        $values = $this->form->getData();
        @unlink($values['details']['pdf']['tmp_name']);
        return ['valid' => false, 'errors' => $this->form->getMessages()];
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
        $this->form->setData($data);
        $this->form->setValidationGroup(['details' => ['mode', $name]]);

        if ($this->form->isValid()) {
            $values = $this->form->getData();
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

        $values = $this->form->getData();
        if (isset($values['details'][$name]['entity']) && !empty($values['details'][$name]['entity'])) {
            $this->repository->getDocumentManager()->remove($values['details'][$name]['entity']);
        }

        return ['valid' => false, 'errors' => $this->form->getMessages() ];
    }
}
