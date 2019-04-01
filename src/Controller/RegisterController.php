<?php

namespace Gastro24\Controller;

use Auth\Entity\User;
use Auth\Entity\UserInterface;
use Auth\Service\Exception\UserAlreadyExistsException;
use Core\Entity\PermissionsInterface;
use Zend\Stdlib\AbstractOptions;
use Zend\View\Model\ViewModel;

/**
 * RegisterController.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class RegisterController extends \CompanyRegistration\Controller\RegistrationController
{

    /**
     * Module options
     *
     * @param AbstractOptions $options
     * @param $repositories
     * @param $authRegisterService
     * @param $logger
     * @param $formManager
     */
    public function __construct(
        AbstractOptions $options,
        $repositories,
        $authRegisterService,
        $logger,
        $formManager
    )
    {
        parent::__construct($options, $repositories, $authRegisterService, $logger, $formManager);

        return $this;
    }

    /**
     * @return array|ViewModel
     */
    public function indexAction()
    {
        /* @var $request \Zend\Http\Request */
        $request                  = $this->getRequest();
        $repositories             = $this->repositories;
        $repositoriesOrganization = $repositories->get('Organizations/Organization');
        $registerService          = $this->authRegisterService;
        $logger                   = $this->logger;
        $formManager              = $this->formManager;
        /* @var \CompanyRegistration\Form\Register $form  */
        $form                     = $formManager->get('Auth\Form\Register',['role'=>$this->params( User::ROLE_USER, User::ROLE_RECRUITER )]);
        /* @var \Auth\Form\Login $formLogin  */
        $formLogin                = $formManager->get('Auth\Form\Login');
        $loginRef = $this->params()->fromQuery('ref', '/de/jobs/edit');
        $formLogin->setAttribute("action", "/de/login?ref=" . $loginRef . "&req=1");
        $formLogin->setAttribute("class", "form-horizontal");
        $viewModel                = new ViewModel();

        if ($request->isPost()) {
            $form->setData($request->getPost()->toArray() ?: array());
//            var_dump($form->isValid());
//            die;
            if ($form->isValid()) {
                try {
                    $mailer = $this->getPluginManager()->get('Mailer');
                    $url = $this->plugin('url');

                    // we cannot check reCaptcha twice (security protection) so we have to remove it
                    /* @var \Zend\Form\FieldSet $register */
                    $register = $form->get('register');
                    $name = $register->get('name')->getValue();
                    $email = $register->get('email')->getValue();
                    /* @var \Auth\Service\Register $registerService */

                    $registerService->setFormFilter($form->getInputFilter());
                    $user = $registerService->proceedWithEmail($name, $email, $mailer, $url);

                    if (isset($user) && $user instanceof UserInterface) {

                        if(User::ROLE_RECRUITER == $register->get('role')->getValue()) {
                            if ($register->has('houseNumber')) {
                                $user->getInfo()->setHouseNumber($register->get('houseNumber')->getValue());
                            }
                            if ($register->has('phone')) {
                                $user->getInfo()->setPhone($register->get('phone')->getValue());
                            }
                            if ($register->has('postalCode')) {
                                $user->getInfo()->setPostalCode($register->get('postalCode')->getValue());
                            }
                            if ($register->has('city')) {
                                $user->getInfo()->setCity($register->get('city')->getValue());
                            }
                            if ($register->has('street')) {
                                $user->getInfo()->setStreet($register->get('street')->getValue());
                            }
                            if ($register->has('gender')) {
                                $user->getInfo()->setGender($register->get('gender')->getValue());
                            }
                        }
                        $repositories->store($user);

                        if(User::ROLE_RECRUITER == $register->get('role')->getValue()) {
                            if ($register->has('organizationName')) {
                                $organizationName = $register->get('organizationName')->getValue();
                                /* @var \Organizations\Entity\Organization $organization */
                                $organization = $repositoriesOrganization->createWithName($organizationName);
                                $organization->setUser($user);

                                if ($register->has('postalCode') && is_object($organization)) {
                                    $organization->getContact()->setPostalcode($register->get('postalCode')->getValue());
                                }

                                if ($register->has('city') && is_object($organization)) {
                                    $organization->getContact()->setCity($register->get('city')->getValue());
                                }

                                if ($register->has('street')) {
                                    $organization->getContact()->setStreet($register->get('street')->getValue());
                                }

                                if ($register->has('houseNumber') && is_object($organization)) {
                                    $organization->getContact()->setHouseNumber($register->get('houseNumber')->getValue());
                                }

                                $permissions = $organization->getPermissions();
                                $permissions->grant($user, PermissionsInterface::PERMISSION_ALL);
                                $repositories->persist($organization);
                            }
                        }

                        // save different invoice address
                        if ($register->has('differentInvoiceAddress') &&
                            filter_var($register->get('differentInvoiceAddress')->getValue(), FILTER_VALIDATE_BOOLEAN)) {
                            /* @var \Zend\Form\FieldSet $invoiceAddress */
                            $invoiceAddressData = $form->get('registerAddressFieldset');
                            $orderSettings = $user->getSettings('Orders');
                            $orderSettings->enableWriteAccess(true);
                            $invoiceAddress = $orderSettings->getInvoiceAddress();

                            if ($invoiceAddressData->has('houseNumber')) {
                                $invoiceAddress->setHouseNumber($invoiceAddressData->get('houseNumber')->getValue());
                            }
//                            if ($invoiceAddress->has('phone')) {
//                                $user->getInfo()->setPhone($register->get('phone')->getValue());
//                            }
                            if ($invoiceAddressData->has('postalCode')) {
                                $invoiceAddress->setZipCode($invoiceAddressData->get('postalCode')->getValue());
                            }
                            if ($invoiceAddressData->has('city')) {
                                $invoiceAddress->setCity($invoiceAddressData->get('city')->getValue());
                            }
                            if ($invoiceAddressData->has('street')) {
                                $invoiceAddress->setStreet($invoiceAddressData->get('street')->getValue());
                            }
                            if ($invoiceAddressData->has('gender')) {
                                $invoiceAddress->setGender($invoiceAddressData->get('gender')->getValue());
                            }

                            $repositories->store($user);

//                            $settings = $this->user->getSettings('Orders');
//                            $settings->enableWriteAccess(true);
//                            $settings = $settings->getInvoiceAddress();
//
//
//                            $orderSettings->setName($info->getDisplayName(false));
//                            $orderSettings->setCompany($this->user->getOrganization()->getOrganization()->getOrganizationName()->getName());
//                            $orderSettings->setCountry($info->getCountry());
//                            $orderSettings->setEmail($info->getEmail());
                        }

                        $viewModel->setTemplate('registration\completed');

                        $this->notification()->success(
                        /*@translate*/ 'An Email with an activation link has been sent, please try to check your email box'
                        );
                        $logger->info('Mail first-login sent to ' . $user->getInfo()->getDisplayName() . ' (' . $email . ')');
                    } else {
                        // this branch is obsolete unless we do decide not to use an exception anymore, whenever something goes wrong with the user

                        $this->notification()->danger(
                        /*@translate*/ 'User can not be created'
                        );
                    }
                } catch (UserAlreadyExistsException $e) {

                    $this->notification()->danger(
                    /*@translate*/ 'User can not be created'
                    );

                    $this->notification()->info(
                        json_encode(
                            array('message' => /*@translate*/ 'user with this e-mail address already exists',
                                'target' => 'register-email-errors')
                        )
                    );
                } catch (\Exception $e) {
                    $this->notification()->danger(
                    /*@translate*/ 'Please fill form correctly'
                    );
                }
            } else {
                $messages = $form->getMessages();
                $this->notification()->danger(
                /*@translate*/ 'Please fill form correctly'
                );
            }
        }
        $viewModel->setVariable('form', $form);
        $viewModel->setVariable('formLogin', $formLogin);

        return $viewModel;
    }
}