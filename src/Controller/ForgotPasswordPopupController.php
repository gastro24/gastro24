<?php

namespace Gastro24\Controller;

use Auth\Service\ForgotPassword;
use Core\Controller\AbstractCoreController;
use Gastro24\Form\ForgotPasswordPopup;
use Laminas\Log\LoggerInterface;

/**
 * ForgotPasswordPopupController.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class ForgotPasswordPopupController extends AbstractCoreController
{
    /**
     * @var ForgotPasswordPopup
     */
    private $form;

    /**
     * @var ForgotPassword
     */
    private $service;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param ForgotPasswordPopup    $form
     * @param ForgotPassword $service
     * @param LoggerInterface        $logger
     */
    public function __construct(
        ForgotPasswordPopup $form,
        ForgotPassword $service,
        LoggerInterface $logger
    ) {
        $this->form = $form;
        $this->service = $service;
        $this->logger = $logger;
    }

    public function indexAction()
    {
        /** @var \Laminas\Http\Request $request */
        $request = $this->getRequest();
        $referer = $request->getHeader('referer');
        try {
            if ($request->isPost()) {
                $this->form->setData($request->getPost()->toArray() ?: array());
                if ($this->form->isValid()) {
                    $mailer = $this->getPluginManager()->get('Mailer');
                    $url = $this->plugin('url');

                    $this->service->proceed($this->form->getInputFilter(), $mailer, $url);

                    $this->notification()->success(
                    /*@translate*/ 'Mail with link for reset password has been sent, please try to check your email box'
                    );
                } else {
                    $this->notification()->danger(
                    /*@translate*/ 'Please fill form correctly'
                    );
                }
            }
        } catch (\Auth\Service\Exception\UserNotFoundException $e) {
            $this->notification()->danger(
            /*@translate*/ 'User cannot be found for specified username or email'
            );
        } catch (\Auth\Service\Exception\UserDoesNotHaveAnEmailException $e) {
            $this->notification()->danger(
            /*@translate*/ 'Found user does not have an email'
            );
        } catch (\Auth\Exception\UserDeactivatedException $e) {
            $this->notification()->danger(
            /*@translate*/ 'Found user is inactive'
            );
        } catch (\Exception $e) {
            $this->logger->crit($e);
            $this->notification()->danger(
            /*@translate*/ 'An unexpected error has occurred, please contact your system administrator'
            );
        }

        return $this->redirect()->toUrl($referer->getUri());
    }
}