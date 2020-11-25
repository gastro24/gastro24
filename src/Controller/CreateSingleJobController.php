<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2018 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\Controller;

use Core\Entity\Collection\ArrayCollection;
use Core\Entity\Hydrator\EntityHydrator;
use Core\Form\Hydrator\Strategy\TreeSelectStrategy;
use Gastro24\Form\InvoiceAddressForm;
use Jobs\Entity\Classifications;
use Orders\Entity\InvoiceAddress;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Session\Container;
use Laminas\View\Helper\ServerUrl;
use Laminas\View\Model\JsonModel;

class CreateSingleJobController extends AbstractActionController
{
    /**
     * @var \Gastro24\Form\SingleJobForm
     */
    private $form;

    /**
     * @var InvoiceAddressForm
     */
    private $invoiceAddressForm;

    public function __construct($form, $invoiceAddressForm)
    {
        $this->form = $form;
        $this->invoiceAddressForm = $invoiceAddressForm;
    }

    public function indexAction()
    {
        /* @var \Laminas\Http\PhpEnvironment\Request $request */
        $request = $this->getRequest();
        $session = new Container('Gastro24_SingleJobData');
        $this->layout()->setTemplate('layouts/layout-create-single');

        if ($request->isPost()) {
            $data = array_merge_recursive($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());

            $data['classifications'] = [
                'employmentTypes' => $data['employmentTypes'],
            ];
            if (isset($data['industries'])) {
                $data['classifications']['industries'] = $data['industries'];
            }
            if (isset($data['professions'])) {
                $data['professions']['industries'] = $data['professions'];
            }

            $this->form->setData($data);
            // !!! WORKAROUND: can be removed if datepicker only allows future dates
            if(isset($data['publishDate']) && !empty($data['publishDate'])) {
                list($day, $month, $year) = explode('/', $data['publishDate']);
                $tmpDate = new \DateTime($year . '-' . $month . '-' . $day);
                $today = new \DateTime();
                if ($tmpDate < $today) {
                    return [
                        'valid' => false,
                        'form' => $this->form,
                        'publishDateError' => true,
                    ];
                }
            }
            if (!$this->form->isValid()) {
                return [
                    'valid' => false,
                    'form' => $this->form,
                ];
            }

            $values = $this->form->getData();
            $values['classifications'] = $data['classifications'];

            $session = new Container('Gastro24_SingleJobData');
            $session->data = serialize($data);
            $session->values = serialize($values);

            if (isset($data['addons']) && count($data['addons'])) {
                return $this->redirect()->toRoute('lang/jobs/single-payment', ['show' => 'options']);
            }

            //kostenlos --> return $this->redirect()->toRoute('lang/jobs/single-payment');
            return $this->redirect()->toRoute('lang/jobs/single-payment', ['show' => 'options']);
        }

        // prefill form
        if (isset($session->values)) {
            $values = unserialize($session->values);
            //$values = array_merge_recursive($values, $this->getRequest()->getFiles()->toArray());
            $values['logo'] = isset($values['logo_url']) ? $values['logo_url'] : null;
            $values['image'] = isset($values['image_url']) ? $values['image_url'] : null;
            $values['classifications'] = [
                'employmentTypes' => $values['classifications']->getEmploymentTypes(),
                'industries' => $values['classifications']->getIndustries(),
                'professions' => $values['classifications']->getProfessions()
            ];
            $this->form->setData($values);
        }

        return [
            'locations' => [],
            'employmentTypes' => [],
            'form' => $this->form,
        ];
    }

    public function paymentAction()
    {
        /* @var \Laminas\Http\PhpEnvironment\Request $request */
        $request = $this->getRequest();
        $this->layout()->setTerminal(true)->setTemplate('layouts/layout-create-single');
        $session = new Container('Gastro24_SingleJobData');
        $mainValues = unserialize($session->values);
        $mainData = unserialize($session->data);
        // prefill company name from first step
        $this->invoiceAddressForm->setData(['invoiceAddress' => ['company' => $mainValues['company']]]);

        $hasAddons = $this->params()->fromRoute('show');

        if ($request->isPost()) {
            $data = array_merge_recursive($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());
            $data['name'] = $data['firstname'] . ' ' . $data['lastname'];
            $data['invoiceAddress']['name'] = $data['firstname'] . ' ' . $data['lastname'];
            $this->invoiceAddressForm->setData($data);

            // remove validation for other address if not checked
            if (!isset($data['toggleOtherAddress'])) {
                $this->invoiceAddressForm->remove('gender-other-address');
                $this->invoiceAddressForm->remove('toggleOtherAddress');
                $this->invoiceAddressForm->remove('otherAddress');
            }

            if (!$this->invoiceAddressForm->isValid()) {
                if ($hasAddons == 'options') {
                    return [
                        'payment' => true,
                        'valid' => false,
                        'invoiceAddressForm' => $this->invoiceAddressForm,
                    ];
                }
                return [
                    'valid' => false,
                    'invoiceAddressForm' => $this->invoiceAddressForm,
                ];
            }

            $session->data = serialize(array_merge_recursive($mainValues, $data));
            $session->values = serialize(array_merge_recursive($mainData, $this->invoiceAddressForm->getData()));

            $session = new Container('Gastro24_SingleJobData');
            if (!$session->values) {
                return $this->redirect()->toRoute('lang/jobs/single');
            }

            $sessionValues = unserialize($session->values);

            $plugin = $this->plugin(Plugin\CreateSingleJob::class);

            $classifications = new Classifications();
            $classificationsHydrator = $this->form->get('classifications')->getHydrator();
            $classifications->setEmploymentTypes($classificationsHydrator->hydrateValue('employmentTypes', $mainData['employmentTypes']));
            if (isset($mainData['industries'])) {
                $classifications->setIndustries($classificationsHydrator->hydrateValue('industries', $mainData['industries']));
            }
            if (isset($mainData['professions'])) {
                $classifications->setProfessions($classificationsHydrator->hydrateValue('professions', $mainData['professions']));
            }
            $sessionValues['classifications'] = $classifications;

            try {
                $plugin($sessionValues);
            } catch (\Exception $e) {
                return $this->redirect()->toRoute('lang/jobs/single-failed');
            }

            // clear session
            $session->exchangeArray([]);

            return $this->redirect()->toRoute('lang/jobs/single-success');
        }

        if ($hasAddons == 'options') {
            return [
                'formattedAddons' => $this->getFormattedAddons($mainData['addons'] ?? []),
                'payment' => true,
                'invoiceAddressForm' => $this->invoiceAddressForm,
                'totalPrice' => $mainValues['totalPrice']
            ];
        }

        return [
            'invoiceAddressForm' => $this->invoiceAddressForm,
            'totalPrice' => $mainValues['totalPrice']
        ];
    }

    private function getFormattedAddons($addonNames)
    {
        $formattedData = [];
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

        foreach ($addonNames as $addonKey) {
            $formattedData[] = $data[$addonKey];
        }

        return $formattedData;
    }

    public function successAction()
    {
        $this->layout()->setTerminal(true)->setTemplate('layouts/layout-create-single');
        return [];
    }

    public function failedAction()
    {
        $this->layout()->setTerminal(true)->setTemplate('layouts/layout-create-single');
        return [];
    }

}
