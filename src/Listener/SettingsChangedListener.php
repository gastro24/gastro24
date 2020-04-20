<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2016 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\Listener;

use Auth\AuthenticationService as AuthenticationService;
use Core\Mail\MailService;
use Core\Options\ModuleOptions;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

class SettingsChangedListener implements ListenerAggregateInterface
{
    /**
     * @var array
     */
    protected $listeners = [];

    /**
     * @var MailService
     */
    protected $mailService;

    /**
     * @var ModuleOptions
     */
    protected $options;
    /**
     * @var AuthenticationService
     */
    protected $authenticationService;

    public function __construct($mailService, ModuleOptions $options, AuthenticationService $authenticationService)
    {
        $this->mailService = $mailService;
        $this->options = $options;
        $this->authenticationService = $authenticationService;
    }

    /**
     * @param EventManagerInterface $events
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $sharedManager = $events->getSharedManager();

        $sharedManager->attach('Settings\Controller\IndexController', 'SETTINGS_CHANGED', [$this, 'onSettingsChanged'], -100);
    }

    public function onSettingsChanged(Event $event)
    {
        if ($this->authenticationService->hasIdentity()) {
            $user = $this->authenticationService->getUser();
            $invoiceAddress = $event->getTarget()->getParam('settings')->getInvoiceAddress();

            /* @var \Core\Mail\HTMLTemplateMessage $mail */
            $mail                   = $this->mailService->get('htmltemplate');
            $mail->setTemplate('gastro24/mail/invoice-address-changed');
            $mail->setSubject('Änderung Rechnungsadresse');
            $mail->setVariables([
                'user' => $user,
                'invoiceAddress' => $invoiceAddress,
            ]);
            $mail->setTo($this->options->getSystemMessageEmail());
            $this->mailService->send($mail);
        }
    }
}
