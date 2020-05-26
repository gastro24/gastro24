<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2018 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\Controller\Plugin;

use Core\Entity\Collection\ArrayCollection;
use Core\Entity\Hydrator\EntityHydrator;
use Core\Entity\Tree\EmbeddedLeafs;
use Core\Entity\Tree\Node;
use Core\Form\Hydrator\Strategy\TreeSelectStrategy;
use Gastro24\Entity\Template;
use Gastro24\Entity\TemplateImage;
use Gastro24\Form\SingleJobHydrator;
use Jobs\Entity\AtsMode;
use Jobs\Entity\Classifications;
use Jobs\Entity\Location;
use Jobs\Entity\Status;
use Orders\Entity\InvoiceAddress;
use Orders\Entity\OrderInterface;
use Orders\Entity\Product;
use Orders\Entity\Snapshot\Job\Builder;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class CreateSingleJob extends AbstractPlugin
{
    private $repositories;
    private $orderOptions;
    private $mailer;

    public function __construct(
        $repositories,
        \Orders\Options\ModuleOptions $orderOptions,
        \Core\Mail\MailService $mailer
    ) {
        $this->repositories = $repositories;
        $this->orderOptions = $orderOptions;
        $this->mailer  = $mailer;
    }

    public function __invoke($values)
    {
        $job   = $this->createJob($values);
        $order = $this->createOrder($job, $values);
        $this->sendMails($job, $order, $values);
    }


    private function createJob($values)
    {
        $jobRepository= $this->repositories->get('Jobs');
        $templateImageRepository = $this->repositories->get('Gastro24/TemplateImage');
        /* @var \Jobs\Entity\Job $job */
        $job = $jobRepository->create();
        $job->setCompany($values['company']);
//        if ('html' == $values['details']['mode']) {
//            $job->getTemplateValues()->setIntroduction($values['details']['introduction']);
//            $job->getTemplateValues()->setDescription($values['details']['description']);
//            $job->getTemplateValues()->setQualifications($values['details']['qualifications']);
//            $job->getTemplateValues()->set('position', $values['details']['position']);
//            $job->getTemplateValues()->setRequirements($values['details']['requirements']);
//            $job->getTemplateValues()->setBenefits($values['details']['benefits']);
//            $template = new Template();
//            $this->jobRepository->getDocumentManager()->persist($template);
//            $this->jobRepository->getDocumentManager()->flush($template);
//
//            if (isset($values['details']['image_id'])) {
//                $image = $this->templateImageRepository->find($values['details']['image_id']);
//                $template->setImage($image);
//            }
//            if (isset($values['details']['logo_id'])) {
//                $logo = $this->templateImageRepository->find($values['details']['logo_id']);
//                $template->setLogo($logo);
//            }
//            $job->addAttachedEntity($template, 'gastro24-template');
//
//        }
        if (isset($values['companyDescription'])) {
            $job->getTemplateValues()->set('companyDescription', $values['companyDescription']);
        }
        if (isset($values['position'])) {
            $job->getTemplateValues()->set('position', $values['position']);
        }
        if (isset($values['pdf_uri'])) {
            $job->setLink($values['pdf_uri']);
        }

        $job->getTemplateValues()->set('companyWebsite', $values['companyWebsite']);
        $job->setTitle($values['jobTitle']);
        $job->setStatus(Status::CREATED);
        $job->setTermsAccepted($values['termsAccepted']);
        $job->setClassifications($values['classifications']);

        // get logo and banner reference
        $template = $job->getAttachedEntity('gastro24-template');
        if (!$template) {
            $template = new Template();
            $this->repositories->store($template);
            $job->addAttachedEntity($template, 'gastro24-template');
        }
        if (isset($data['logo_id'])) {
            $file = $templateImageRepository->find($data['logo_id']);
            $template->setLogo($file);
        }
        if (isset($data['bannerImage_id'])) {
            $file = $templateImageRepository->find($data['bannerImage_id']);
            $template->setImage($file);
        }

        $locations = new ArrayCollection();
        foreach ($values['locations'] as $locStr) {
            $loc = new Location($locStr);
            $locations->add($loc);
        }
        $job->setLocations($locations);

        switch ($values['enableOnlineApplication']) {
            case 'applicationMail':
                //get email and cc
                $emailAddress = (isset($values['applicationEmail'])) ? $values['applicationEmail'] : $values['invoiceAddress']['email'];
                $job->setAtsMode(new AtsMode(AtsMode::MODE_EMAIL, $emailAddress));
                $job->setContactEmail($emailAddress);
                break;
            case 'directLink':
                // set directlink
                $applyUri = $values['applicationUri'];
                $job->setUriApply($applyUri);
                break;
            case 'noOnlineApplication':
                $job->setAtsMode(new AtsMode(AtsMode::MODE_NONE));
                break;
            default:
                $job->setAtsMode(new AtsMode(AtsMode::MODE_NONE));
                break;
        }

        if ($values['publishDate']) {
            $job->setDatePublishStart(new \DateTime($values['publishDate']));
        }

        $jobRepository->store($job);

        return $job;
    }

    private function sendMails($job, $order, $values)
    {
        $applicationOptionData = [];
        switch ($values['enableOnlineApplication']) {
            case 'applicationMail':
                $applicationOption = 'E-Mail Adresse für Bewerbungen';
                $applicationOptionData = [
                    'E-Mail' => $values['applicationEmail'],
                ];
                break;
            case 'directLink':
                $applicationOption = 'Direktlink zum Bewerberformular';
                $applicationOptionData = [
                    'Link' => $values['applicationUri'],
                ];
                break;
            case 'noOnlineApplication':
                $applicationOption = 'Keine Online-Bewerbung';
                break;
            default:
                $applicationOption = 'Keine Online-Bewerbung';
                break;
        }

        $this->mailer->send($this->mailer->get(
            'Gastro24/SingleJobMail',
            [
                'template' => 'gastro24/mail/single-job-pending',
                'email'    => $values['invoiceAddress']['email'],
                'name'     => $values['firstname'] . ' ' . $values['lastname'],
                'subject'  => 'Ihre Anzeige wartet auf Freischaltung.',
                'vars'     => [
                    'job' => $job,
                    'invoice' => $order->getInvoiceAddress(),
                ],
            ]
        ));

        $values['invoiceAddress']['name'] = $values['firstname'] . ' ' . $values['lastname'];

        $mailVars = [
            'job' => $job,
            'order' => $order,
            'invoice' => $values['invoiceAddress'],
            'applicationOption' => $applicationOption,
            'applicationOptionData' => $applicationOptionData,
            'companyWebsite' => $values['companyWebsite'],
            'companyDescription' => $values['companyDescription'],
        ];
        if (isset($values['otherAddress'])) {
            $mailVars['otherAddress'] = $values['otherAddress'];
            $mailVars['otherAddress']['name'] = $values['firstname-other-address'] . ' ' . $values['lastname-other-address'];
        }

        $this->mailer->send($this->mailer->get(
            'Gastro24/SingleJobMail',
            [
                'template' => 'gastro24/mail/single-job-created',
                'admin'    => true,
                'subject'  => 'Eine Einzelanzeige wurde erstellt.',
                'vars'     => $mailVars,
            ]
        ));
    }

    private function createOrder(\Jobs\Entity\Job $job, $values)
    {
        $orderRepository = $this->repositories->get('Orders');
        $invoiceAddress = new InvoiceAddress();
        foreach ($values['invoiceAddress'] as $setter => $value) {
            $invoiceAddress->{"set$setter"}($value);
        }
        $invoiceAddress->setName($values['firstname'] . ' ' . $values['lastname']);
        $invoiceAddress->setGender($values['gender']);

        // overwrite invoice address
        if (isset($values['otherAddress']) && count($values['otherAddress'])) {
            $invoiceAddress->setName($values['firstname-other-address'] . ' ' . $values['lastname-other-address']);
            $invoiceAddress->setGender($values['gender-other-address']);
            $invoiceAddress->setStreet($values['otherAddress']['street']);
            $invoiceAddress->setCountry($values['otherAddress']['country']);
            $invoiceAddress->setRegion($values['otherAddress']['region']);
            $invoiceAddress->setZipCode($values['otherAddress']['zipCode']);
            $invoiceAddress->setCity($values['otherAddress']['city']);
        }

        $snapshotBuilder = new Builder();
        $snapshot = $snapshotBuilder->build($job);
        $snapshot->setOrganizationName($values['company']);
        $products = new ArrayCollection();

        $product = new Product();
        $product->setName('Einzelinserat')
                ->setProductNumber(1)
                ->setQuantity(1);

        $products->add($product);
        $data = [
            'type' => OrderInterface::TYPE_JOB,
            'taxRate' => $this->orderOptions->getTaxRate(),
            'price' => 0,
            'invoiceAddress' => $invoiceAddress,
            'currency' => $this->orderOptions->getCurrency(),
            'currencySymbol' => $this->orderOptions->getCurrencySymbol(),
            'entity' => $snapshot,
            'products' => $products,
        ];

        $order = $orderRepository->create($data);
        $orderRepository->store($order);

        return $order;
    }
}
