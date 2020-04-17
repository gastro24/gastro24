<?php

namespace Gastro24\Service;

use Auth\Entity\User;
use Auth\Listener\Events\AuthEvent;
use Auth\Service\Exception\UserAlreadyExistsException;
use Zend\InputFilter\InputFilterInterface;
use Zend\Mvc\Controller\Plugin\Url;
use Core\Controller\Plugin\Mailer;
use Core\EventManager\EventManager;

/**
 * Register.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class RegisterConfirmation
{
    /**
     * @var \Auth\Repository\\User
     */
    private $userRepository;

    /**
     * @var \Zend\Authentication\AuthenticationService
     */
    private $authenticationService;

    /**
     * Auth/Events
     *
     * @var \Zend\EventManager\EventManagerInterface
     */
    private $events;

    public function __construct(\Auth\Repository\User $userRepository, \Zend\Authentication\AuthenticationService $authenticationService)
    {
        $this->userRepository = $userRepository;
        $this->authenticationService = $authenticationService;
    }

    /**
     * @param \Zend\EventManager\EventManagerInterface $events
     *
     * @return \Auth\Service\RegisterConfirmation
     */
    public function setEventManager($events)
    {
        $this->events = $events;

        return $this;
    }

    /**
     * @return \Zend\EventManager\EventManagerInterface
     */
    public function getEventManager()
    {
        if (!$this->events) {
            $this->events = new EventManager();
            $this->events->setEventPrototype(new AuthEvent());
        }

        return $this->events;
    }

    public function proceed($userId)
    {
        $user = $this->userRepository->findOneBy(['id' => $userId], ['allowDeactivated' => true]);
        if (!$user) {
            throw new Exception\UserNotFoundException('User cannot be found');
        }

        /* \Auth\Entity\Info */
        //$user->getInfo()->setEmailVerified(true);
        $user->setEmail($user->getInfo()->getEmail()); // Set verified email as primary email.
        $this->userRepository->store($user);
        $this->authenticationService->getStorage()->write($user->getId());

        /* @var EventManager $events
         * @var \Auth\Listener\Events\AuthEvent $event
         */
        $events = $this->getEventManager();
        $event  = $events->getEvent(AuthEvent::EVENT_USER_CONFIRMED, $this);
        $event->setUser($user);
        $events->triggerEvent($event);
    }
}