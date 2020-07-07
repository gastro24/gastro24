<?php

namespace Gastro24\Controller;

use Auth\Service\Exception\UserNotFoundException;
use Core\Factory\ContainerAwareInterface;
use Gastro24\Form\RegisterCompanyForm;
use Laminas\Log\LoggerInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Core\Entity\PermissionsInterface;
use Organizations\Entity\OrganizationReference;

/**
 * RegisterConfirmation.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class RegisterConfirmationController extends AbstractActionController
{
    /**
     * @var \Gastro24\Service\RegisterConfirmation
     */
    private $registerConfirmationService;

    /**
     * @var \Gastro24\Service\Register
     */
    private $registerService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    protected $formManager;

    /**
     * @var \Auth\Repository\User
     */
    protected $repositories;

    public function __construct(
        \Gastro24\Service\RegisterConfirmation $registerConfirmationService,
        \Gastro24\Service\Register $registerService,
        LoggerInterface $logger,
        $formManager,
        $repositories
    )
    {
        $this->registerConfirmationService = $registerConfirmationService;
        $this->registerService = $registerService;
        $this->logger = $logger;
        $this->formManager = $formManager;
        $this->repositories = $repositories;
    }

    public function organizationAction()
    {
        /** @var \Laminas\Http\Request $request */
        $request = $this->getRequest();
        $userId = $this->params()->fromRoute('userId', null);
        $userRepository = $this->repositories->get('Auth/User');
        //$user = $userRepository->find($userId);
        $user = $userRepository->findOneBy(['id' => $userId], ['allowDeactivated' => true]);

        if (!$user) {
            $this->notification()->danger(/*@translate*/ 'User cannot be found');
            return $this->redirect()->toRoute('lang/register');
        }

        // user already confirmed
        if ($user->getInfo()->isEmailVerified() && $user->hasOrganization()) {
            return $this->redirect()->toRoute('lang/jobs/manage', ['action' => 'edit']);
        }

        $this->layout()->setTemplate('layouts/layout-without-header');

        /* @var RegisterCompanyForm $registerCompanyForm  */
        $registerCompanyForm = $this->formManager->get(RegisterCompanyForm::class);
        $viewModel  = new ViewModel();
        $viewModel->setVariable('form', $registerCompanyForm);

        if ($request->isPost()) {
            $postData = $request->getPost()->toArray() ?: [];
            $registerCompanyForm->setData($postData);
            if ($registerCompanyForm->isValid()) {
                try {
                    $user = $userRepository->find($userId);
                    if (isset($user) && $user instanceof \Auth\Entity\UserInterface) {
                        $user->getInfo()->setPhone($registerCompanyForm->get('phone')->getValue());
                        $user->getInfo()->setPostalCode($registerCompanyForm->get('postalCode')->getValue());
                        $user->getInfo()->setCity($registerCompanyForm->get('city')->getValue());
                        $user->getInfo()->setStreet($registerCompanyForm->get('street')->getValue());
                        $user->getInfo()->setCountry($registerCompanyForm->get('country')->getValue());
                        $user->getInfo()->setGender($registerCompanyForm->get('gender')->getValue());
                        $this->repositories->store($user);

                        // save organization
                        $organizationName = $registerCompanyForm->get('organizationName')->getValue();
                        $organization = $this->createOrganization($organizationName, $user);

                        // save invoice address
                        $vatId = $registerCompanyForm->get('vat')->getValue();
                        $representative = $registerCompanyForm->get('representative')->getValue();
                        $invoiceAddress = $this->createInvoiceAddress($user, $vatId, $representative);

                        $this->registerService->proceedCompanyRegisteredMail($user, $organization, $invoiceAddress);

                        $this->notification()->success('Sie haben sich erfolgreich als Arbeitgeber registriert und können nun Ihre erste Stellenanzeige erfassen.');

                        return $this->redirect()->toRoute('lang/my-organization', ['action' => 'edit']);
                    } else {
                        // this branch is obsolete unless we do decide not to use an exception anymore, whenever something goes wrong with the user
                        $this->notification()->danger(/*@translate*/ 'User can not be created');
                    }
                } catch (UserAlreadyExistsException $e) {

                    $this->notification()->danger(/*@translate*/ 'User can not be created');

                    $this->notification()->info(
                        json_encode(
                            array('message' => /*@translate*/ 'user with this e-mail address already exists',
                                'target' => 'register-email-errors')
                        )
                    );
                } catch (\Exception $e) {
                    $this->notification()->danger(/*@translate*/ 'Please fill form correctly');
                }
            }
            else {
                $this->notification()->danger(/*@translate*/ 'Please fill form correctly');

                return $viewModel;
            }
        }
        else {
            try {
                $this->registerConfirmationService->proceed($userId);
                $registerCompanyForm->get('email')->setValue($user->getInfo()->getEmail());
                $registerCompanyForm->get('firstname')->setValue($user->getInfo()->getFirstName());
                $registerCompanyForm->get('lastname')->setValue($user->getInfo()->getLastName());

//            $this->notification()->info(
//            /*@translate*/ 'User email verified successfully. You need to set a password to log in.'
//            );

                return $viewModel;
            } catch (UserNotFoundException $e) {
                $this->notification()->danger(/*@translate*/ 'User cannot be found');
            } catch (\Exception $e) {
                $this->logger->crit($e);
                $this->notification()->danger(/*@translate*/ 'An unexpected error has occurred, please contact your system administrator');
            }
        }

        return $this->redirect()->toRoute('lang/register');
    }

    private function createOrganization($organizationName, $user)
    {
        $organizationRepository = $this->repositories->get('Organizations/Organization');
        $info = $user->getInfo();
        /* @var \Organizations\Entity\Organization $organization */
        $organization = $organizationRepository->createWithName($organizationName);
        $organization->setUser($user);

        $organization->getContact()->setPostalcode($info->getPostalCode());
        $organization->getContact()->setCity($info->getCity());
        $organization->getContact()->setStreet($info->getStreet());
        $organization->getContact()->setCountry($info->getCountry());
        //$organization->getContact()->setHouseNumber($registerCompanyForm->get('houseNumber')->getValue());

        $permissions = $organization->getPermissions();
        $permissions->grant($user, PermissionsInterface::PERMISSION_ALL);
        $this->repositories->persist($organization);

        // needed otherwise company is empty in UserRegisteredListener
        $reference = new OrganizationReference($user->getId(), $this->repositories->get('Organizations\Entity\Organization'));
        $user->setOrganization($reference);
        $this->repositories->store($user);

        return $organization;
    }

    private function createInvoiceAddress($user, $vatId, $representative = null)
    {
        $info = $user->getInfo();
        $settings = $user->getSettings('Orders');
        $settings->enableWriteAccess(true);
        /* @var \Orders\Entity\InvoiceAddress $invoiceAddress */
        $invoiceAddress = $settings->getInvoiceAddress();
        $org = $user->getOrganization()->getOrganization();

        $invoiceName = $representative ?? $info->getDisplayName(false);
        $invoiceAddress->setGender($info->getGender());
        $invoiceAddress->setName($invoiceName);

        $invoiceAddress->setStreet($info->getStreet());
        $invoiceAddress->setHouseNumber($info->getHouseNumber());
        $invoiceAddress->setZipCode($info->getPostalCode());
        $invoiceAddress->setCity($info->getCity());
        $invoiceAddress->setCountry($info->getCountry());
        $invoiceAddress->setEmail($info->getEmail());
        $invoiceAddress->setCompany($org->getOrganizationName()->getName());
        $invoiceAddress->setVatId($vatId);

        $this->repositories->store($user);

        return $invoiceAddress;
    }
}