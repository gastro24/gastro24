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
use Gastro24\Entity\Template;
use Jobs\Entity\AtsMode;
use Jobs\Entity\Location;
use Jobs\Entity\Status;
use Orders\Entity\InvoiceAddress;
use Orders\Entity\OrderInterface;
use Orders\Entity\Product;
use Orders\Entity\Snapshot\Job\Builder;
use Laminas\Mvc\Controller\Plugin\AbstractPlugin;

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

        // save landingpage categories
        if (isset($values['category']) && !empty($values['category'])) {
            $categories = [$values['category']];

            if (isset($values['subcategory']) && !empty($values['subcategory'])) {
                $categories[] = $values['subcategory'];
            }
            $job->getTemplateValues()->set('categories', $categories);
            $job->getTemplateValues()->set('category', end($categories));
        }
        if (isset($values['publishDate'])) {
            $job->getTemplateValues()->set('publishDate', $values['publishDate']);
        }
        if (isset($values['companyDescription'])) {
            $job->getTemplateValues()->set('companyDescription', $values['companyDescription']);
        }
        if (isset($values['position'])) {
            $job->getTemplateValues()->set('position', $values['position']);
        }
        if (isset($values['companyWebsite'])) {
            $job->getTemplateValues()->set('companyWebsite', $values['companyWebsite']);
        }
        if (isset($values['pdf_uri'])) {
            $job->setLink($values['pdf_uri']);
        }

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
        if (isset($values['logo_id'])) {
            $file = $templateImageRepository->find($values['logo_id']);
            $template->setLogo($file);
        }
        if (isset($values['bannerImage_id'])) {
            $file = $templateImageRepository->find($values['bannerImage_id']);
            $template->setImage($file);
        }

        $locations = new ArrayCollection();
        $locIndex = 1;
        while (isset($values['location_' . $locIndex])) {
            $loc = new Location($values['location_' . $locIndex]);

            // loc has no zipCode
            if (empty($loc->getPostalCode())) {
                $loc->setPostalCode($values['locationZipCode_' . $locIndex]);
            }
            if (empty($loc->getStreetname())) {
                $loc->setStreetname($values['locationStreet_' . $locIndex]);
            }

            $locations->add($loc);
            $locIndex++;
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
                $job->setAtsMode(new AtsMode(AtsMode::MODE_URI, $applyUri));
                break;
            case 'noOnlineApplication':
            default:
                $job->setAtsMode(new AtsMode(AtsMode::MODE_NONE));
                break;
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
                    'lastname' => $values['lastname'],
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
            'companyWebsite' => $values['companyWebsite'] ?? '',
            'companyDescription' => $values['companyDescription'],
            'isFreeSingle' => $values['isFreeSingle']
        ];

        if (isset($values['otherAddress'])) {
            $mailVars['otherAddress'] = $values['otherAddress'];
            $mailVars['otherAddress']['name'] = $values['firstname-other-address'] . ' ' . $values['lastname-other-address'];
        }

        // HINT: for first implementation, just send plain text in email
        if (isset($values['addons'])) {
            $mailVars['addons'] = $this->buildAddonsArray($values['addons']);
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

    private function buildAddonsArray($addons)
    {
        $data = [
            'addon_renewal' => [
                'name' => 'Verlängerung 90 Tage',
                'price' => 15,
            ],
            'addon_startpage' => [
                'name' => 'Top-Listing Homepage',
                'price' => 95,
            ],
            'addon_top_result' => [
                'name' => 'Top-Listing Suche',
                'price' => 55,
            ],
            'addon_highlight' => [
                'name' => 'Farbliche Hervorhebung',
                'price' => 25,
            ],
            'addon_facebook' => [
                'name' => 'Social Media Werbung',
                'price' => 150,
            ],
        ];
        $preparedData = [];

        foreach ($addons as $key => $name) {
            $preparedData[] = [
                'name' => $data[$name]['name'],
                'price' => $data[$name]['price']
            ];
        }

        return $preparedData;
    }

    private function createOrder(\Jobs\Entity\Job $job, $values)
    {
        $orderRepository = $this->repositories->get('Orders');
        $invoiceAddress = new InvoiceAddress();
        foreach ($values['invoiceAddress'] as $setter => $value) {
            $invoiceAddress->{"set$setter"}($value);
        }
        $invoiceAddress->setGender($values['gender']);

        // overwrite invoice address
        if (isset($values['otherAddress']) && !empty($values['otherAddress']['street']) && count($values['otherAddress'])) {
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
