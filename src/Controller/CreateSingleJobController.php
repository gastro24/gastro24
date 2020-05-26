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
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Helper\ServerUrl;
use Zend\View\Model\JsonModel;

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
        /* @var \Zend\Http\PhpEnvironment\Request $request */
        $request = $this->getRequest();
        $session = new Container('Gastro24_SingleJobData');
        $this->layout()->setTemplate('layouts/layout-create-single');

        if ($request->isPost()) {
            $data = array_merge_recursive($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());
            $this->form->setData($data);
            if (!$this->form->isValid()) {
                return [
                    'valid' => false,
                    'form' => $this->form,
                ];
            }

            $values = $this->form->getData();
            $hydrator = $this->form->getEmploymentTypesHydrator();
            $employmentTypes = $hydrator->hydrateValue('employmentTypes', $values['employmentTypes']);

            $classifications = new Classifications();
            $classifications->setEmploymentTypes($employmentTypes);
            $values['classifications'] = $classifications;

            $session = new Container('Gastro24_SingleJobData');
            $session->data = serialize($data);
            $session->values = serialize($values);

            if (isset($data['addons']) && count($data['addons'])) {
                return $this->redirect()->toRoute('lang/jobs/single-payment', ['show' => 'options']);
            }

            return $this->redirect()->toRoute('lang/jobs/single-payment');
            //return $values;

            //return $this->process();


//            $postData = $this->getRequest()->getPost()->toArray();
//            $filesData = $this->getRequest()->getFiles()->toArray();
//
//            if (count($postData['addons'])) {
//                //TODO show payment page
//                return [
//                    'payment' => true,
//                    'form' => $this->form,
//                ];
//            }
//
//            $values = $this->process($postData, $filesData);
//            return $this->complete();
        }

        // prefill form
        if (isset($session->values)) {
            $values = unserialize($session->values);
            //$values = array_merge_recursive($values, $this->getRequest()->getFiles()->toArray());
            $values['logo'] = isset($values['logo_url']) ? $values['logo_url'] : null;
            $values['image'] = isset($values['image_url']) ? $values['image_url'] : null;
            $values['classifications'] = [
                'employmentTypes' => $values['classifications']->getEmploymentTypes()->getValues()
            ];
            $this->form->setData($values);
        }

//        if ('complete' == $request->getQuery('do')) {
//            return $this->complete();
//        }

        return [
            'locations' => [],
            'employmentTypes' => [],
            'form' => $this->form,
        ];
    }

    private function process()
    {
        $data = array_merge_recursive($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());
//        $data = array_merge_recursive($postData, $filesData);
        $this->form->setData($data);

        // check for pdf or position - remove required filter for excluded
//        if ($postData['position']) {
//            //$this->form->get('pdf')->get
//        }

        if (!$this->form->isValid()) {
            return [
                'valid' => false,
                'form' => $this->form,
            ];
        }

        $values = $this->form->getData();

//        switch ($postData['enableOnlineApplication']) {
//            case 'applicationMail':
//                //get email and cc
//                break;
//            case 'directLink':
//                // set directlink
//                break;
//            case 'noOnlineApplication':
//                // no online application
//                break;
//            default:
//                break;
//        }

        //$values = $this->form->getData();
        $values = $data;

//        if ($values['pdf']) {
//            $serverUrl = new ServerUrl();
//            $basePath  = $this->getRequest()->getBasePath();
//            $values['pdf'] = $serverUrl('/' . $basePath . str_replace('public/', '', $values['pdf']['tmp_name']));
//        }
//
//        if (isset($data['details']['logo_id'])) {
//            $values['details']['logo_id'] = $data['details']['logo_id'];
//            $values['details']['logo_url'] = $data['details']['logo_url'];
//        }
//
//        if (isset($data['details']['image_id'])) {
//            $values['details']['image_id'] = $data['details']['image_id'];
//            $values['details']['image_url'] = $data['details']['image_url'];
//        }

        $hydrator = $this->form->getEmploymentTypesHydrator();
        $employmentTypes = $hydrator->hydrateValue('employmentTypes', $values['employmentTypes']);

        $classifications = new Classifications();
        $classifications->setEmploymentTypes($employmentTypes);
        $values['classifications'] = $classifications;

        $session = new Container('Gastro24_SingleJobData');
        $session->data = serialize($data);
        $session->values = serialize($values);

        $values['isProcessed'] = true;
        $values['invoiceAddressForm'] = $this->form;

        return $values;
    }

    public function paymentAction()
    {
        /* @var \Zend\Http\PhpEnvironment\Request $request */
        $request = $this->getRequest();
        $this->layout()->setTerminal(true)->setTemplate('layouts/layout-create-single');

        $hasAddons = $this->params()->fromRoute('show');

        if ($request->isPost()) {
            $data = array_merge_recursive($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());
            $this->invoiceAddressForm->setData($data);

            // remove validation for other address if not checked
            if (!$data['toggleOtherAddress']) {
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
            $session = new Container('Gastro24_SingleJobData');
            $mainValues = unserialize($session->values);
            $mainData = unserialize($session->data);
            $session->data = serialize(array_merge_recursive($mainValues, $data));
            $session->values = serialize(array_merge_recursive($mainData, $this->invoiceAddressForm->getData()));

            $session = new Container('Gastro24_SingleJobData');
            if (!$session->values) {
                return $this->redirect()->toRoute('lang/jobs/single');
            }

            $sessionValues = unserialize($session->values);

            $plugin = $this->plugin(Plugin\CreateSingleJob::class);
            // build classification with employment types
            $hydrator = $this->form->getEmploymentTypesHydrator();
            $employmentTypes = $hydrator->hydrateValue('employmentTypes', $mainValues['employmentTypes']);

            $classifications = new Classifications();
            $classifications->setEmploymentTypes($employmentTypes);
            $values['classifications'] = $classifications;
            $values = array_merge($values, $sessionValues);

            try {
                $plugin($values);
            } catch (\Exception $e) {
                return $this->redirect()->toRoute('lang/jobs/single-failed');
            }

            // clear session
            $session->exchangeArray([]);

            return $this->redirect()->toRoute('lang/jobs/single-success');
        }

        if ($hasAddons == 'options') {
            return [
                'payment' => true,
                'invoiceAddressForm' => $this->invoiceAddressForm,
            ];
        }

        return [
            'invoiceAddressForm' => $this->invoiceAddressForm,
        ];
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

//    private function complete()
//    {
//        $session = new Container('Gastro24_SingleJobData');
//        $values  = $session->values;
//
//        if (!$values) {
//            return $this->redirect()->toRoute('lang/jobs/single');
//        }
//
//        $plugin = $this->plugin(Plugin\CreateSingleJob::class);
//
//        try {
//            $plugin(unserialize($values));
//        } catch (\Exception $e) {
//            return [
//                'isError' => true,
//            ];
//        }
//
//        $session->exchangeArray([]);
//
//        return [
//            'isSuccess' => true
//        ];
//
//    }

}
