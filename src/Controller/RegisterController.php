<?php

namespace Gastro24\Controller;

use Auth\Entity\UserInterface;
use Auth\Service\Exception\UserAlreadyExistsException;
use Laminas\Stdlib\AbstractOptions;
use Laminas\View\Model\ViewModel;

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
        /* @var $request \Laminas\Http\Request */
        $request                  = $this->getRequest();
        $registerService          = $this->authRegisterService;
        $logger                   = $this->logger;
        $formManager              = $this->formManager;

        /* @var \Gastro24\Form\SimpleRegisterForm $simpleRegisterForm  */
        $simpleRegisterForm = $formManager->get('Auth\Form\SimpleRegister');
        $viewModel  = new ViewModel();

        if ($request->isPost()) {
            $postData = $request->getPost()->toArray() ?: array();
            $simpleRegisterForm->setData($postData);
            if ($simpleRegisterForm->isValid()) {
                try {
                    $mailer = $this->getPluginManager()->get('Mailer');
                    $url = $this->plugin('url');

                    $firstname = trim($simpleRegisterForm->get('firstname')->getValue());
                    $lastname = trim($simpleRegisterForm->get('lastname')->getValue());
                    $email = $simpleRegisterForm->get('email')->getValue();
                    $password = $simpleRegisterForm->get('password')->getValue();

                    /* @var \Gastro24\Service\Register $registerService */
                    $registerService->setFormFilter($simpleRegisterForm->getInputFilter());
                    $registerService->setPassword($password);
                    $user = $registerService->proceedWithNameAndEmail($firstname, $lastname, $email, $mailer, $url);

                    if (isset($user) && $user instanceof UserInterface) {
                        $viewModel->setVariable('successMessage',
                            'Bitte verifizieren Sie Ihre E-Mail-Adresse, in dem Sie im soeben versendeten E-Mail auf den Link klicken');

                        $this->notification()->success(
                        /*@translate*/ 'An Email with an activation link has been sent, please try to check your email box'
                        );
                        $logger->info('Mail first-login sent to ' . $user->getInfo()->getDisplayName() . ' (' . $email . ')');
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
            } else {
                $this->notification()->danger(/*@translate*/ 'Please fill form correctly');
            }
        }
        $viewModel->setVariable('form', $simpleRegisterForm);

        return $viewModel;
    }
}