<?php

namespace Gastro24\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Gastro24\Entity\Product\AbstractProduct;
use Gastro24\Entity\UserProduct;
use Jobs\Entity\Job;
use Jobs\Entity\Status;
use Interop\Container\ContainerInterface;
use Solr\Bridge\Manager;
use Solr\Filter\EntityToDocument\JobEntityToSolrDocument as EntityToDocumentFilter;

/**
 * JobDeletedListener.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class JobDeletedListener implements EventSubscriber
{
    /**
     * @var Manager
     */
    protected $solrManager;

    /**
     * @var EntityToDocumentFilter
     */
    protected $entityToDocumentFilter;

    /**
     * @var SolrClient
     */
    protected $solrClient;

    /**
     * @param Manager $manager
     * @param EntityToDocumentFilter $entityToDocumentFilter
     */
    public function __construct(Manager $manager, EntityToDocumentFilter $entityToDocumentFilter)
    {
        $this->solrManager = $manager;
        $this->entityToDocumentFilter = $entityToDocumentFilter;
    }

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
        if (!$job->isDraft()) {
            $product->decreaseJobCount();
        }

        if ($job->getStatus() && ($job->getStatus()->getName() == Status::INACTIVE)) {
            $product->decreaseInactiveJobCount();
        }

        //@see \Solr\Listener\JobEventSubscriber
        $client = $this->getSolrClient();
        $client->deleteByIds($this->entityToDocumentFilter->getDocumentIds($job));
        // commit to index & optimize it
        $client = $this->getSolrClient();
        $client->commit(true, false);
        $client->optimize(1, true, false);
    }

    /**
     * @return SolrClient
     * @since 0.27
     */
    protected function getSolrClient()
    {
        if (!isset($this->solrClient)) {
            $path = $this->solrManager->getOptions()->getJobsPath();
            $this->solrClient = $this->solrManager->getClient($path);
        }

        return $this->solrClient;
    }

    /**
     * @param ContainerInterface $container
     * @return JobDeletedListener
     */
    public static function factory(ContainerInterface $container)
    {
        $options = $container->get('Solr/Options/Module');
        return new static($container->get('Solr/Manager'), new EntityToDocumentFilter($options));
    }
}