<?php

namespace Gastro24\Service;

use Auth\Entity\User;
use Auth\Listener\Events\AuthEvent;
use Auth\Service\Exception\UserAlreadyExistsException;
use Auth\Service\Register as BaseRegisterService;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\Mvc\Controller\Plugin\Url;
use Core\Controller\Plugin\Mailer;

/**
 * Register.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class Register extends BaseRegisterService
{

    /**
     * @var string
     */
    protected $firstname;

    /**
     * @var string
     */
    protected $lastname;

    /**
     * @var string
     */
    protected $password;


    /**
     * @param $firstname
     * @param $lastname
     * @param $email
     * @param Mailer $mailer
     * @param Url $url
     * @return User|null
     */
    public function proceedWithNameAndEmail($firstname, $lastname, $email, Mailer $mailer, Url $url)
    {
        $this->setFirstname($firstname);
        $this->setLastname($lastname);
        $this->setEmail($email);
        $this->setMailer($mailer);
        $this->setUrlPlugin($url);
        if ($this->proceedUser()) {
            $this->proceedMail();
            if (isset($this->user)) {
                return $this->user;
            }
        }
        return null;
    }

    /**
     * @param InputFilterInterface $filter
     * @param \Core\Controller\Plugin\Mailer $mailer
     * @param Url $url
     * @return null|User
     * @throws \Auth\Service\Exception\UserAlreadyExistsException
     */
    public function proceed(InputFilterInterface $filter, \Core\Controller\Plugin\Mailer $mailer, Url $url)
    {
        $this->setFormFilter($filter);
        $this->setMailer($mailer);
        $this->setUrlPlugin($url);
        if ($this->proceedUser()) {
            $this->proceedMail();
            if (isset($this->user)) {
                return $this->user;
            }
        }
        return null;
    }

    /**
     *
     * @since 0.29 Replace call to deprecated setFormattedSubject with setSubject
     */
    public function proceedMail()
    {
        $siteName = $this->options->getSiteName();
        $url = $this->getUrlPlugin();
        $user = $this->getUser();
        if (isset($user)) {
            $confirmationLink = $url->fromRoute(
                'lang/register-organization-confirmation',
                array('userId' => $user->getId()),
                array('force_canonical' => true)
            );

            $userEmail              = $user->getInfo()->getEmail();
            $userName               = $user->getInfo()->getDisplayName();
            $mailService            = $this->getMailService();
            /* @var \Core\Mail\HTMLTemplateMessage $mail */
            $mail                   = $mailService->get('htmltemplate');
            $mail->user             = $user;
            $mail->name             = $userName;
            $mail->confirmationlink = $confirmationLink;
            $mail->siteName         = $siteName;
            $mail->setTemplate('mail/register');
            $mail->setSubject('E-Mail Adresse verifizieren');
            $mail->setTo($userEmail);
            $mailService->send($mail);
        }
    }

    public function proceedCompanyRegisteredMail($user, $organization, $invoiceAddress)
    {
        $userRepository = $this->getUserRepository();
        $user->getInfo()->setEmailVerified(true);
        //$user->setStatus(\Auth\Entity\Status::ACTIVE);
        $userRepository->store($user);

        $mail = $this->mailService->get('htmltemplate');
        $mail->setTemplate('gastro24/mail/abo-created');
        $mail->setVariables([
            'user' => $user,
            'organization' => $organization,
            'invoiceAddress' => $invoiceAddress,
        ]);
        $mail->setSubject('Eine Jahres-Job wurde erstellt.');
        $mail->setTo($this->options->getSystemMessageEmail());
        $this->mailService->send($mail);

        /* @var \Core\EventManager\EventManager $events */
        /* @var \Auth\Listener\Events\AuthEvent $event */
        $events = $this->getEventManager();
        $event  = $events->getEvent(AuthEvent::EVENT_USER_REGISTERED, $this);
        $event->setUser($user);
        $events->triggerEvent($event);
    }

    /**
     * @return User
     * @throws UserAlreadyExistsException
     */
    protected function proceedUser()
    {
        if (!isset($this->user)) {
            $userRepository = $this->getUserRepository();
            $firstname = $this->getFirstname();
            $lastname = $this->getLastname();
            $password = $this->getPassword();
            $email = $this->getEmail();
            $role = User::ROLE_RECRUITER;

            if (($userRepository->findByLoginOrEmail($email))) {
                //return Null;
                throw new UserAlreadyExistsException('User already exists');
            }

            $user = $userRepository->create(
                array(
                    'login' => $email,
                    'role' => $role,
                )
            );

            $info = $user->getInfo();
            $info->setEmail($email);
            $info->setFirstName($firstname);
            $info->setLastName($lastname);
            $info->setEmailVerified(false);

            $user->setPassword($password);

            $userRepository->store($user);
            $this->setUser($user);

            /* @var \Core\EventManager\EventManager $events */
            /* @var \Auth\Listener\Events\AuthEvent $event */
            $events = $this->getEventManager();
            $event  = $events->getEvent(AuthEvent::EVENT_USER_REGISTERED, $this);
            $event->setUser($user);
            $events->triggerEvent($event);
        }

        return $this->getUser();
    }

    /**
     * @return null|string
     */
    protected function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param $firstname
     * @return string
     */
    protected function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this->firstname;
    }

    /**
     * @return null|string
     */
    protected function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param $lastname
     * @return string
     */
    protected function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this->lastname;
    }
    /**
     * @return null|string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param $lastname
     * @return string
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this->password;
    }

    /**
     * @return null|string
     */
    protected function getEmail()
    {
        return $this->email;
    }
}