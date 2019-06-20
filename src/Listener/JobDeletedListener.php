<?php

namespace Gastro24\Listener;

use Cv\Entity\Attachment;
use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Gastro24\Entity\Product\AbstractProduct;
use Gastro24\Entity\UserProduct;
use Jobs\Entity\Job;
use Jobs\Entity\JobSnapshotStatus;
use Jobs\Entity\Status;
use Jobs\Listener\Events\JobEvent;
use Core\Repository\RepositoryService;
use Interop\Container\ContainerInterface;
use Orders\Entity\Order;

/**
 * JobDeletedListener.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class JobDeletedListener implements EventSubscriber
{
    /**
     * @see \Doctrine\Common\EventSubscriber::getSubscribedEvents()
     */
    public function getSubscribedEvents()
    {
        return array('postRemoveEntity');
    }

    /**
     * Removes attachments
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function postRemoveEntity(LifecycleEventArgs $eventArgs)
    {
        $job = $eventArgs->getDocument();
        if (!$job instanceof Job) {
            return;
        }

        /* @var \Auth\Entity\User $owner
         * @var \Gastro24\Entity\UserProduct $productWrapper
         */
        $company = $job->getOrganization();

        if (!$company) {
            return;
        }

        $owner = $company->getUser();
        if (!$owner) {
            return;
        }

        $productWrapper = $owner->getAttachedEntity(UserProduct::class);
        if (!$productWrapper) {
            return;
        }

        /** @var AbstractProduct $product */
        $product = $productWrapper->getProduct();
        $product->decreaseJobCount();

    }
}