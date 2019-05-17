<?php

namespace Gastro24\Listener;

use Jobs\Entity\JobSnapshotStatus;
use Jobs\Entity\Status;
use Jobs\Listener\Events\JobEvent;
use Core\Repository\RepositoryService;
use Interop\Container\ContainerInterface;
use Orders\Entity\Order;

/**
 * ExpiredJobListener.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class ExpiredJobListener
{
    /**
     * @var RepositoryService
     */
    private $repositories;
    private $mailer;

    public function __construct(
        RepositoryService $repositories,
        \Core\Mail\MailService $mailer
    ) {
        $this->repositories = $repositories;
        $this->mailer = $mailer;
    }

    public function __invoke(JobEvent $event)
    {
        $orderRepo = $this->repositories->get('Orders');
        $job = $event->getJobEntity();

        if (Status::EXPIRED != $event->getParam('status')->getName()) {
            return;
        }

        /** @var Order $order */
        $order = $orderRepo->findByJobId($job->getId());

        if (!$order) {
            return;
        }
        
        $this->mailer->send($this->mailer->get(
            'Gastro24/SingleJobMail',
            [
                'template' => 'mail/job-expired',
                'subject'  => /*@translate */ 'Ihre Anzeige ist abgelaufen.',
                'email'    => $order->getInvoiceAddress()->getEmail(),
                'name'     => $order->getInvoiceAddress()->getName(),
                'vars'     => [
                    'job' => $job,
                    'gender' => $order->getInvoiceAddress()->getGender(),
                    'userName' => $order->getInvoiceAddress()->getName()
                ],
            ]
        ));
    }
}